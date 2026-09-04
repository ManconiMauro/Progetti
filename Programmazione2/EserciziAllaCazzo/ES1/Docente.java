package EserciziAllaCazzo.ES1;

public class Docente {
    //Overview:creare una classe docente che contenga le carratteristiche di un docente universitario
    //(nome, cognome, età, codice)

    //Attributi
    final private String nome;
    final private String cognome;
    private int età;    //età ho deciso di non metterla final a differenza degli altri attributi perchè l'età può cambiare
    final private String codice;

    //costruttore
    public Docente(String nome,String cognome,int età, String codice) throws IllegalArgumentException{
        //modifies:this nome,cognome,età,codice
        //effects:inizializza una nuova classe docente con gli attributi nome,cognome,età,codice
        //se questi non sono validi lancia una IllegalArgumentException
        if(nome.equals("")||cognome.equals("")||età<0||codice.equals("")){
            throw new IllegalArgumentException("I dati inseriti non sono  validi");
        }
        this.nome=nome;
        this.cognome=cognome;
        this.età=età;
        this.codice=codice;
    }

    //variante di rappresentazione
    public boolean RepOk(){
        //controllo che le stringhe non siano vuote
        if(nome.equals("")||cognome.equals("")||codice.equals("")){
            return false;
        }
        //controllo che l'età non sia negativa o che non sia sopra i 67 perchè c'e la pensine di anzianità
        if(età<0||età>67){
            return false;
        }
        return true;
    }

    //getter
    public String getCognome() {
        //effect:restituisce this.cognome
        return cognome;
    }

    //getter
    public int getEta() {
        //effects:restituisce this.età
        return età;
    }

    //getter
    public String getCodice() {
        //effects:restituisce this.codice
        return codice;
    }

    @Override
    public String toString() {
        return "Docente: \n nome: "+this.nome+"\n cognome: "+this.cognome+"\n età: "+this.età+"\n codice identificativo: "+this.codice;
    }
}
