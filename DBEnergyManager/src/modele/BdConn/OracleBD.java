package modele.BdConn;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

import lib.LectureClavier;

public class OracleBD {

	/* D�but : information de connexion � la base de donn�e */
	static final String CONN_URL = "jdbc:oracle:thin:@//localhost:1521/XE";
	static final String USER = "zverevi";
	static final String PASSWD = "zverevi";

	/* Fin : information de connexion � la base de donn�e */

	// D�claration de l'objet qui �tablira la connexion avec la base
	private static Connection conn;

	// Initialisation de la lecture clavier
	private LectureClavier lecteur = new LectureClavier();

	// Objet d'acc�s qui sera partag� par tout les objets de l'application
	// (singleton)
	private static OracleBD bdd;
	Connection base = null;

	// R�cup�re l'objet Base de donn�e si il est d�j� cr��, ou l'initialise
	// (singleton)
	public static synchronized OracleBD getInstance() {
		if (bdd == null) {
			bdd = new OracleBD();
		}
		return bdd;
	}

	// Initialisation de la connexion � la base de donn�e.
	public OracleBD() {
		try {
			DriverManager.registerDriver(new oracle.jdbc.driver.OracleDriver());
			conn = DriverManager.getConnection(CONN_URL, USER, PASSWD);
			conn.setAutoCommit(false);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

	public void commit() {
		Statement statment;
		try {
			conn.commit();
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	public void closeConn() {
		
		
		try {
			conn.close();
			bdd = null;
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}

	public void rollback() {
		Statement statment;
		try {
			/*
			statment = conn.createStatement();
			statment.executeQuery("rollback");
			statment.close();*/
			conn.rollback();
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

	public void bddExecuteUpdate(String request) {
		try {
			PreparedStatement statment = conn.prepareStatement(request);
			statment.executeUpdate();
			commit();
			statment.close();
		} catch (SQLException e) {
			ErrorCodeMessage er = new ErrorCodeMessage(e);
			er.afficherMessageErreur();
			System.exit(0);
		}
	}

	public ResultSet bddExecuteSelect(String request) {
		try {
			java.sql.Statement exec = conn.prepareStatement(request);
			ResultSet res = exec.executeQuery(request);
			return res;
		} catch (SQLException e) {
			ErrorCodeMessage er = new ErrorCodeMessage(e);
			er.afficherMessageErreur();
			System.exit(0);
			return null;
		}
	}

	public void setIsolation(String niveau_isolation) {

		if (niveau_isolation.equals("READ_COMMITTED") || niveau_isolation.equalsIgnoreCase("r")) {
			try {
				conn.setTransactionIsolation(Connection.TRANSACTION_READ_COMMITTED);
			} catch (SQLException e) {
				e.printStackTrace();
			}
		} else if (niveau_isolation.equals("SERIALIZABLE") || niveau_isolation.equalsIgnoreCase("s")) {
			try {
				conn.setTransactionIsolation(Connection.TRANSACTION_SERIALIZABLE);
			} catch (SQLException e) {
				e.printStackTrace();
			}
		} else {
			System.out.print("Les autres modes ne sont pas g�r�s par Oracle !");
		}
	}

}
