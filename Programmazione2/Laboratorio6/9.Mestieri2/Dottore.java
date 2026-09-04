public class Dottore extends Persona{
    //Overview: implementare una classe Dottore che estende Persona e ha come una Specializzazione (String) e una parcella per le visite (double).

    //Attributi
    private String specializzazione;
    private Double parcella;

    //costruttori
    public Dottore(String nome, String specializzazione, Double parcella) throws IllegalArgumentException{
        //modifies:this
        //effect:crea un nuovo Dottore e chima una illegalArgumentException se i dati inseriti non sono consoni
        if(nome.equals("")||specializzazione.equals("")||parcella<=0||parcella==null){
            throw new IllegalArgumentException("Dati del dottore inseriti male");
        }
        setNome(nome);
        this.specializzazione=specializzazione;
        this.parcella=parcella;
    }

    //getter e setter
    public String getSpecializzazione() {
        return specializzazione;
    }

    public void setSpecializzazione(String specializzazione) {
        this.specializzazione = specializzazione;
    }

    public Double getParcella() {
        return parcella;
    }

    public void setParcella(Double parcella) {
        this.parcella = parcella;
    }    

    //metodi
    public Double calcolaParcella(int visite){
        return this.parcella*visite;
    }
}
