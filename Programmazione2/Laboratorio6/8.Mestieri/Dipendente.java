public class Dipendente extends Persona{
    //Overview:creare una classe dipendente che implementi Persona 
    //la class conterrà anche gli attributi una retribuzione annuale (double), un anno di assunzione (int) e un codice identificativo (String)

    //Attributi
    private Double salario;
    private int annoAssunzione;
    private String codiceIdentificativo;

    //costruttore
    public Dipendente(String nome,Double salario, int annoAssunzione, String codiceIdentificativo) throws IllegalArgumentException{
        //modifies:this, nome di persona
        //effects:inizializza un nuovo dipendente con un salario, anno di assunzone e un codice identificativo
        //se uno di questi ha valore non valido lancia una illegalArgumentException
        super(nome);
        if(salario==null||codiceIdentificativo.equals("")) throw new IllegalArgumentException("Dipendente non valido, i dati inseriti sono errati");
        this.salario = salario;
        this.annoAssunzione = annoAssunzione;
        this.codiceIdentificativo = codiceIdentificativo;
    }

    //getters
    public Double getSalario() {
        return salario;
    }

    public void setSalario(Double salario) {
        this.salario = salario;
    }

    public int getAnnoAssunzione() {
        return annoAssunzione;
    }

    //setters
    public void setAnnoAssunzione(int annoAssunzione) {
        //modifies:this
        this.annoAssunzione = annoAssunzione;
    }

    public String getCodiceIdentificativo() {
        //modifies:this
        return codiceIdentificativo;
    }

    public void setCodiceIdentificativo(String codiceIdentificativo) {
        //modifies:this
        this.codiceIdentificativo = codiceIdentificativo;
    }

    //metodi
    public boolean AnnoPrima(){
        //effects:restituisce true se l'anno di assunzione è prima del 2374 altrimenti false
        if(this.annoAssunzione<2374){
            return true;
        }
        return false;
    }
}
