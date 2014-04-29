
import java.sql.CallableStatement;
import java.sql.Connection;
import java.sql.Date;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.ResultSetMetaData;
import java.sql.SQLException;
import java.sql.Types;
import com.google.gson.JsonArray;
import com.google.gson.JsonPrimitive;


//Calls a stored procedure with the procedure's name being passed as the first argument.
public class PerformQuery {

	/**
	 * @param args
	 */

	 //args[0] is the type of query
	 //args[1] is the role value necessary for the query
	 //args[2] is the user's role value
	//args[3] For User queries, contains the admin's password for decryption
	//args[3 - n] For Order queries that pass parameters, contains the parameters.
	//The correct form is args[i] contains the data type and args[i+1] contains the data.
	public static void main(String[] args) {

        //This checks to make sure the current user is allowed to make the query
		if((Integer.parseInt(args[1]) == 0 && Integer.parseInt(args[2]) < 1) ||
			(Integer.parseInt(args[1]) == 1 && Integer.parseInt(args[2]) < 2) ||
			(Integer.parseInt(args[1]) == 2 && Integer.parseInt(args[2]) < 3))
				System.exit(-1);
		
        //admin password
		String pass = null;

        //User query requires admin password for decryption
        if(Integer.parseInt(args[1]) == 2 && (args[0].equals("ViewUsers") || args[0].equals("ReEncrypt") || args[0].equals("EditUser")
        		|| args[0].equals("AddUser")))
        	pass = args[3];

		String query = args[0];
		String[][] queryData = null;
		ResultSet rs = null;
		
		//Json array is necessary in order to pass the array to php.
		//JsonArray outerArray = new JsonArray();
				
		String url = "jdbc:mysql://localhost:3306/mydb"; //where the database is

		Connection con = null;

		try {
			con = DriverManager.getConnection(url, "root", "newsqlpassword");
		} catch (SQLException e1) {
			// TODO Auto-generated catch block
			//System.out.println("Error connecting to database");
			e1.printStackTrace();
		}

		CallableStatement cstmt = null;
		
		try {
			cstmt = con.prepareCall("{call "+query+"()}"); //query with no parameters
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		try {
		    //Order queries that have no parameters.
			//These queries are for purchasers where only 7 fields are viewable.
			if(Integer.parseInt(args[1]) < 2 && hasNoParameters(args[0])){
			
				rs = cstmt.executeQuery();
				rs.last();
				int numRows = rs.getRow();
				rs.beforeFirst();
				
				queryData = new String[numRows][18];

				int i = 0;


				while(rs.next()){
                    //Get the data from the resultset. If not a string, convert to string.
					String orderId = rs.getString(1);
					String requestor = rs.getString(2);
					String reqEmail = rs.getString(3);
					String descrip = rs.getString(4);
					String vendor = rs.getString(5);
					double amount = rs.getDouble(6);
					String amt = Double.toString(amount);
					Boolean urgent = rs.getBoolean(7);
					String urg = "";
					if(urgent)
						urg = "true";
	                else
	                    urg = "false";
					//String attachment = rs.getString(7);
				
                    //put the query data into a 2d string array
					queryData[i][0] = orderId;
					queryData[i][1] = " ";
					queryData[i][2] = " ";
					queryData[i][3] = " ";
					queryData[i][4] = " ";
					queryData[i][5] = " ";
					queryData[i][6] = urg;
					queryData[i][7] = " ";
					queryData[i][8] = vendor;
					queryData[i][9] = descrip;
					queryData[i][10] = " ";
					queryData[i][11] = " ";
					queryData[i][12] = requestor;
					queryData[i][13] = reqEmail;
					queryData[i][14] = amt;
					queryData[i][15] = " ";
					queryData[i][16] = " ";
					queryData[i][17] = " ";
					
					i++;
				}
				
				//add each row element of data to the inner json array
		        //Then add the row's inner json array to an outer json array to create a 2d json array.
				for(int j=0; j<queryData.length; j++){

					JsonArray innerArray = new JsonArray();

					for(int k=0; k<queryData[j].length; k++){
						
						JsonPrimitive jp;
						
						if(queryData[j][k] == null)
							jp = new JsonPrimitive(" ");
						else
							jp= new JsonPrimitive(queryData[j][k]);

						innerArray.add(jp);
					}
					
					System.out.println(innerArray.toString());

					//outerArray.add(innerArray);
					
					//postQueryData(outerArray, "localhost/var/www/PerformQuery.php"); //TODO: get correct path for performquery.php
				}
			}

			//These order queries are built dynamically. Any parameters must be passed from the php script in this manner:
			//args[i] = the type of data
			//args[i+1] = the data itself
			else if(Integer.parseInt(args[1]) < 3 && isViewOrdersQuery(args[0])){

				int numParams = getNumParameters(args[0]);

				query += "(";

                //the query needs ?s for each parameter
				for(int i=0; i<numParams; i++){

					query += "?";

					if(i != numParams-1)
						query += ", ";
				}

				query += ")}";

				cstmt = con.prepareCall("{call "+query);

				cstmt.clearParameters();
				
				int k = 1;

                //set the parameter values
				for(int i=3; i<args.length; i+=2){

					if(args[i].equals("date")){
						Date d = Date.valueOf(args[i+1]);
						cstmt.setDate(k, d);
					}

					else if(args[i].equals("boolean")){
						Boolean b = Boolean.valueOf(args[i+1]);
						cstmt.setBoolean(k, b);
					}

					else if(args[i].equals("double")){
						double d = Double.valueOf(args[i+1]);
						cstmt.setDouble(k, d);
					}

					else if(args[i].equals("int")){
						int n = Integer.valueOf(args[i+1]);
						cstmt.setInt(k, n);
					}

					else if(args[i].equals("string"))
						cstmt.setString(k, args[i+1]);
					
					k++;
				}

				rs = cstmt.executeQuery();

				//need the meta data to find the number of columns in the query data
				ResultSetMetaData md = rs.getMetaData();

				rs.last();
				int numRows = rs.getRow();
				rs.first();
				
				while(rs.isBeforeFirst())
					rs.next();
				
				int numCols = md.getColumnCount();

				queryData = new String[numRows][numCols];

                //add the query data to the 2d array
				for(int i=0; i<numRows; i++){
					for(int j=0; j<numCols; j++){

						if(md.getColumnClassName(j+1).toLowerCase().equals("java.lang.double")){
							//System.out.println("1 ");
							
								double val = rs.getDouble(j+1);
								if(rs.wasNull())
									queryData[i][j] = null;
								else{
									queryData[i][j] = Double.toString(val);
							}
						}

						else if (md.getColumnClassName(j+1).toLowerCase().equals("java.sql.date")){
							//System.out.println("2 ");
							
								Date val = rs.getDate(j+1);
								if(rs.wasNull())
									queryData[i][j] = null;
								else{
									queryData[i][j] = val.toString();
							}
						}

						else if (md.getColumnClassName(j+1).toLowerCase().equals("java.lang.integer")){
							//System.out.println("3 ");
							
								int val = rs.getInt(j+1);
								if(rs.wasNull())
									queryData[i][j] = null;
								else{
									queryData[i][j] = Integer.toString(val);
							}
						}

						else if (md.getColumnClassName(j+1).toLowerCase().equals("java.lang.boolean")){
							//System.out.println("4 ");
							
								boolean val = rs.getBoolean(j+1);
								if(rs.wasNull())
									queryData[i][j] = null;
								else{
									queryData[i][j] = Boolean.toString(val);
							}
						}

						else if (md.getColumnTypeName(j+1).toLowerCase().equals("varbinary") || md.getColumnTypeName(j+1).toLowerCase().equals("varchar")){
							//System.out.println("5 ");
							String val = rs.getString(j+1);
							if(rs.wasNull())
								queryData[i][j] = null;
							else
								queryData[i][j] = val;
						}	
					}
					rs.next(); //next row
				}
				
				//add each row element of data to the json array
		        //Then print it to stdout
				for(int i=0; i<queryData.length; i++){	

					JsonArray innerArray = new JsonArray();

					for(int j=0; j<queryData[i].length; j++){

						JsonPrimitive jp = null;
						
						if(queryData[i][j] != null)
							jp = new JsonPrimitive(queryData[i][j]);
						else
							jp = new JsonPrimitive(" ");

						innerArray.add(jp);
					}
					System.out.println(innerArray.toString());
				}
			}
			
			//Non-user queries that don't return a resultset such as updating queries.
			else if(Integer.parseInt(args[1]) < 3 && pass == null && !isViewOrdersQuery(query)){
				
				int numParams = getNumParameters(args[0]);
				int numAffected = 0; //number of rows affected by update, or a value indicating success.

				query += "(";

                //the query needs ?s for each parameter
				for(int i=0; i<numParams; i++){

					query += "?";

					if(i != numParams-1)
						query += ", ";
				}

				query += ")}";

				cstmt = con.prepareCall("{call "+query);

				cstmt.clearParameters();
				
				int k = 1;
				
                //set the parameter values
				for(int i=3; i<args.length; i+=2){
					//System.out.println("I'm working here!");
					while(i<args.length && args[i].equals("null")){ //when passing the null string, args[i+1] is not going to be data.
						cstmt.setNull(k, Types.INTEGER);
						i++;
						k++;
					}
					
					if(i>= args.length)
						break;
					
					if(args[i].equals("date")){
						Date d = Date.valueOf(args[i+1]);
						cstmt.setDate(k, d);
					}

					else if(args[i].equals("boolean")){
						Boolean b = Boolean.valueOf(args[i+1]);
						cstmt.setBoolean(k, b);
					}

					else if(args[i].equals("double")){
						double d = Double.valueOf(args[i+1]);
						cstmt.setDouble(k, d);
					}

					else if(args[i].equals("int")){
						int n = Integer.valueOf(args[i+1]);
						cstmt.setInt(k, n);
					}

					else if(args[i].equals("string"))
						cstmt.setString(k, args[i+1]);

					k++;
				}
				
				numAffected = cstmt.executeUpdate();

				if(numAffected > 0){
					System.out.println("1");
					System.exit(0);
				}
				else{
					System.out.println("0");
					System.exit(0);
				}
				
			}
            //User queries that require decryption
			else if(Integer.parseInt(args[1]) == 2 && pass != null){
				
				int numParams = getNumParameters(args[0]);
				int numAffected = 0; //number of rows affected by update, or a value indicating success.
				
				query += "(";

                //the query needs ?s for each parameter
				for(int i=0; i<numParams; i++){

					query += "?";

					if(i != numParams-1)
						query += ", ";
				}

				query += ")}";

				cstmt = con.prepareCall("{call "+query);

				cstmt.clearParameters();
				
				int k = 1;
				
                //set the parameter values
				for(int i=3; i<args.length; i+=2){
					
					if(args[i].equals("null")){ //when passing the null string, args[i+1] is not going to be data.
						cstmt.setNull(k, Types.INTEGER);
						i-=1;
						continue;
					}
					
					else if(args[i].equals("date")){
						Date d = Date.valueOf(args[i+1]);
						cstmt.setDate(k, d);
					}

					else if(args[i].equals("boolean")){
						Boolean b = Boolean.valueOf(args[i+1]);
						cstmt.setBoolean(k, b);
					}

					else if(args[i].equals("double")){
						double d = Double.valueOf(args[i+1]);
						cstmt.setDouble(k, d);
					}

					else if(args[i].equals("int")){
						int n = Integer.valueOf(args[i+1]);
						cstmt.setInt(k, n);
					}

					else if(args[i].equals("string"))
						cstmt.setString(k, args[i+1]);
					
					k++;
				}
				
					
				if(args[0].equals("ViewUsers")){
					
					rs = cstmt.executeQuery();

					//need the meta data to find the number of columns in the query data
					ResultSetMetaData md = rs.getMetaData();
					rs.last();
					int numRows = rs.getRow();
					rs.first();
					int numCols = md.getColumnCount();

					queryData = new String[numRows][numCols];
					
	                //add the query data to the 2d array
					for(int i=0; i<numRows; i++){
						for(int j=0; j<numCols; j++){
							
							if(md.getColumnClassName(j+1).toLowerCase().equals("java.lang.double")){
								//System.out.println("1 ");
								double val = rs.getDouble(j+1);
								queryData[i][j] = Double.toString(val);
							}

							else if (md.getColumnClassName(j+1).toLowerCase().equals("java.sql.date")){
								//System.out.println("2 ");
								Date val = rs.getDate(j+1);
								queryData[i][j] = val.toString();
							}

							else if (md.getColumnClassName(j+1).toLowerCase().equals("java.lang.integer")){
								//System.out.println("3 ");
								int val = rs.getInt(j+1);
								queryData[i][j] = Integer.toString(val);
							}

							else if (md.getColumnClassName(j+1).toLowerCase().equals("java.lang.boolean")){
								//System.out.println("4 ");
								boolean val = rs.getBoolean(j+1);
								queryData[i][j] = Boolean.toString(val);
							}

							else if (md.getColumnTypeName(j+1).toLowerCase().equals("varbinary") || md.getColumnTypeName(j+1).toLowerCase().equals("varchar")){
								//System.out.println("5 ");
								queryData[i][j] = rs.getString(j+1);
							}
							
						}
						rs.next(); //next row
					}
					//add each row element of data to the json array
			        //Then print it to stdout
					for(int i=0; i<queryData.length; i++){

						JsonArray innerArray = new JsonArray();

						for(int j=0; j<queryData[i].length; j++){
							
							JsonPrimitive jp = new JsonPrimitive(queryData[i][j]);

							innerArray.add(jp);
						}
						
						System.out.println(innerArray.toString());		
					}

				}
				
				else{
					numAffected = cstmt.executeUpdate();
					
					if(numAffected > 0){
						System.out.println("1");
						System.exit(0);
					}
					else{
						System.out.println(0);
						System.exit(0);
					}
				}
				
			}

		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			rs = null;
		}

		try {
			cstmt.close();
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	
	}
    //These queries don't pass any parameters
	public static boolean hasNoParameters(String str){
		if(str.equals("AllOrders") || str.equals("AllApproved") || str.equals("AllPurchased") || str.equals("AwaitingApproval")
				|| str.equals("AwaitingDelivery") || str.equals("AwaitingPurchase"))
					return true;
		else
			return false;
	}

    //Returns the number of parameters that are being passed for the query
	public static int getNumParameters(String str){

		if(str.equals("FindOrderByEmail") || str.equals("FindOrderById") || str.equals("FindOrderByName") || str.equals("FindOrderByPartName")
				|| str.equals("RemoveUser") || str.equals("RemoveOrder") || str.equals("ViewUsers") || str.equals("GetRoleByEmail") || str.equals("RemoveOrder")
				|| str.equals("GetEmail"))
					return 1;

		else if(str.equals("OrdersByDateRange") || str.equals("AcctOrdersByDateRange") || str.equals("ReEncrypt") || str.equals("ForgotPassword")
				|| str.equals("ChangePassword"))
			return 2;

		else if(str.equals("AddUser"))
			return 7;
		
		else if(str.equals("EditUser"))
			return 6;

		else if(str.equals("AddOrder"))
			return 12;
		
		else if(str.equals("EditOrder"))
			return 18;
			
		else
			return 0;
	}
	
	//Returns true only if the query returns a result set for displaying data
	public static boolean isViewOrdersQuery(String str){
		
		if(str.equals("FindOrderByEmail") || str.equals("FindOrderById") || str.equals("FindOrderByName") || str.equals("FindOrderByPartName")
				|| str.equals("OrdersByDateRange") || str.equals("AcctOrdersByDateRange") || str.equals("AllOrders")
				|| str.equals("AllApproved") || str.equals("AllPurchased") || str.equals("AwaitingApproval") || str.equals("AwaitingDelivery")
				|| str.equals("AwaitingPurchase") || str.equals("AcctAllApproved") || str.equals("AcctAwaitingApproval") || str.equals("AcctAllOrders")
				|| str.equals("GetRoleByEmail") || str.equals("GetEmail")){
			
			return true;
		}
		
		else
			return false;
	}
	
}

