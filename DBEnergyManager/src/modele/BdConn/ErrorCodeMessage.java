package modele.BdConn;

import java.sql.SQLException;

public class ErrorCodeMessage {

	private SQLException e;
	
	public ErrorCodeMessage(SQLException e){
		this.e = e;
	}
	
	public void afficherMessageErreur(){
		System.err.println("Erreur s'est produite");
		if(e.getErrorCode()==1){
			this.ErrorCode1();
		}else if(e.getErrorCode()==20001){
			this.ErrorCode_20001();
		}else if(e.getErrorCode()==20002){
			this.ErrorCode_20002();
		}else if(e.getErrorCode()==20003){
			this.ErrorCode_20003();
		}else if(e.getErrorCode()==20004){
			this.ErrorCode_20004();
		}else if(e.getErrorCode()==20007){
			this.ErrorCode_20007();
		}else if(e.getErrorCode()==20005){
			this.ErrorCode_20005();
		}else if(e.getErrorCode()==20006){
			this.ErrorCode_20006();
		}else{
			this.ErrorCodeDefault();
			e.printStackTrace();
		}
	}



	private void ErrorCode_20001() {
		System.err.println("Err.Trigger. Il est impossible de modifier le prix total de la commande dans la base");
	}	
	private void ErrorCode_20002() {
		System.err.println("Err.Trigger. Il est impossible de modifier les informations d'une photo en attente");
	}
	private void ErrorCode_20003() {
		System.err.println("Err.Trigger. Il est impossible de modifier la valeur d'un code promo dans la base");
	}
	private void ErrorCode_20004() {
		System.err.println("Err.Trigger. Il n'existe plus aucun fichier d'un album commandé dans la base");
	}
	private void ErrorCode_20005() {
		System.err.println("Err.Trigger. Un même IdAlbum ne peut être au plus que dans une seule des tables suivantes : Livre / Calendrier / Agenda");
	}	
	private void ErrorCode_20006() {
		System.err.println("Err.Trigger. Vous n'avez pas ou plus les droits sur une des composantes de l'album");
	}	
	private void ErrorCode_20007() {
		System.err.println("Err.Trigger. Vous n'avez pas ou plus le droit sur une des photos dans un album commandé");
	}
	private void ErrorCodeDefault() {
		System.err.println("Erreur au niveau de la base non-reconnue");
	}

	private void ErrorCode1(){
		//ORA-00001: Unique constraint (string.string) violated
		if(e.getMessage().contains("C_MAIL")){
			System.err.println("L'adresse existe déjà dans la Base. Veuiller reexecuter la transaction");
		}
	}
}
