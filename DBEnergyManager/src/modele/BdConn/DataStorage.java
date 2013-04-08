package modele.BdConn;

import java.sql.ResultSet;
import java.sql.SQLException;


public class DataStorage {

	private int IDPROJET = -1;
	private int IDEQU = -1;
	
	public void insertProjet(String nom, String lieu){
		String requeteProjetExistant = "select IDPROJET from projet where lieu='"+lieu+"' and nom_projet='"+nom+"'";
		//Recupérer IDPROJET
		ResultSet rs2 = OracleBD.getInstance().bddExecuteSelect(requeteProjetExistant);
		int projetExistant = 0;
		try {
			while (rs2.next()) {
				projetExistant= rs2.getInt("IDPROJET");
			}
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		if (projetExistant != 0){
			IDPROJET = projetExistant;
			return;
		}
		String requeteProchainIDPROJET = "select s_inc_projet.nextval as nextIDPROJET from dual";
		//Recupérer IDPROJET
		ResultSet rs = OracleBD.getInstance().bddExecuteSelect(requeteProchainIDPROJET);
		try {
			while (rs.next()) {
				IDPROJET= rs.getInt("nextIDPROJET");
				//System.out.println("ID du PROJET ["+nom+"]["+lieu+"] : "+ IDPROJET);
			}
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		try {
			rs.close();
		} catch (SQLException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}
		
		//INSERER PROJET
		String requeteInsertPROJET = "insert into projet values (s_inc_projet.currval, '"+nom+"','"+lieu+"')";
		try {
			OracleBD.getInstance().bddExecuteUpdate(requeteInsertPROJET);
			OracleBD.getInstance().commit();
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	public void insertEquipement(String nomEquipement, String maisonEquipement){
		//Recupérer IDEQU
		String requeteProchainIDEQU = "select s_inc_equipement.nextval as nextIDEQU from dual";
		ResultSet rs = OracleBD.getInstance().bddExecuteSelect(requeteProchainIDEQU);
		try {
			while (rs.next()) {
				IDEQU= rs.getInt("nextIDEQU");
				//System.out.println("ID de l'equipement : "+ IDEQU);
			}
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		try {
			rs.close();
		} catch (SQLException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}
		
		//INSERER Equipement
		String requeteInsertEquipement = "insert into equipement values " +
				"(" +
					"s_inc_equipement.currval, " +
					"'"+nomEquipement+"'," +
					maisonEquipement+","+
					IDPROJET+
				")";
		try {
			OracleBD.getInstance().bddExecuteUpdate(requeteInsertEquipement);
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	public void insertReleve(String date_Releve, String heure_Releve, String etat_Releve, String conso_Releve){
		//INSERER Releve
		String requeteInsertReleve = "insert into suivi_releve values " +
				"(" +
					IDEQU +","+
					"'"+date_Releve+"'," +
					"'"+heure_Releve+"'," +
					etat_Releve+"," +
					conso_Releve+
				")";
		try {
			OracleBD.getInstance().bddExecuteUpdate(requeteInsertReleve);
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	public void insertMaison(String num_maison){
		String requeteMaisonExistant = "select IDMAISON from maison where IDMAISON="+num_maison;
		//Recupérer IDPROJET
		ResultSet rs2 = OracleBD.getInstance().bddExecuteSelect(requeteMaisonExistant);
		int maisonExistant = -1;
		try {
			while (rs2.next()) {
				maisonExistant= rs2.getInt("IDMAISON");
			}
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		if (maisonExistant != -1){
			return;
		}
		//INSERER Maison
		String requeteInsertMaison = "insert into maison values " +
				"(" +
				 num_maison+
				")";
		try {
			OracleBD.getInstance().bddExecuteUpdate(requeteInsertMaison);
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
