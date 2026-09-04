public class Paziente extends Persona{
    //Overview:implementare anche una classe Paziente che estende Persona, un Paziente oltre gli attributi di Persona ha un codice identificativo (String).

    //Attributi
    private String codiceIdentificativo;

    //costruttore
    public Paziente(String nome, String codiceIdentificativo) throws IllegalArgumentException{
        //modifies:this
        //effects:crea un nuovo paziente usando nome e codice e lancia una IllegalArgumentException se questi sono nulli
        if(nome.equals("")||codiceIdentificativo.equals("")) throw new IllegalArgumentException("Dati del paziente inseriti non validi");
        setNome(nome);
        this.codiceIdentificativo=codiceIdentificativo;
    }

    //getter
    public String getCodiceIdentificativo() {
        return codiceIdentificativo;
    }

    //setter
    public void setCodiceIdentificativo(String codiceIdentificativo) {
        this.codiceIdentificativo = codiceIdentificativo;
    }
}
