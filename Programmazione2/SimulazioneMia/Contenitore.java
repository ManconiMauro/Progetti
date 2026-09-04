public  abstract class Contenitore implements Comparable<Contenitore>{
    //Overview:Modellare una classe astratta contenitore che identifichi un contenitore generico   
    
    //Attributi
    private final String liquido;
    private double quantita;
    private final double parametro;

    //constructors
    public Contenitore(String liquido, double quantita, double parametro) throws IllegalArgumentException{
        //Modifies:this
        //Effects:inizializza un nuovo contenitore con i parametri dati
        //se liquido è vuoto o nullo allora lancia una IllegalArgumentException
        //se quantità, parametro sono <=0 lancia una IllegalArgumentException
        //se la quantità super la capacità del contenitore allora lancia un ExceededCapacityException
        if(liquido.equals("")||liquido==null){
            throw new IllegalArgumentException("Liquido nullo o vuoto");
        }
        if(quantita<0||parametro<0){
            throw new IllegalArgumentException("Parametri inseriti non validi");
        }
        this.liquido=liquido;
        this.quantita=quantita;
        this.parametro=parametro;
    }

    //getter
    public String getLiquido() {
        //Effects:ritorna il liquido dentro al contenitore
        return liquido;
    }

    //getter
    public double getQuantita() {
        //Effects:ritorna la quantità di liquido nel contenitore
        return quantita;
    }

    //getter
    public double getParametro() {
        //Effects:ritorna il parametro inserito nel contenitore
        return parametro;
    }

    //setter 
    public void setQuantita(double quantita){
        //Modifies:this.quantita
        //effects:cambia il valore di quantita
        this.quantita=quantita;
    }

    //methods
    public double GetCapienza(){
        //effects:ritorna la capienza del contenitore
        return getQuantita();
    }

    //Invariante di rappresentazione
    public boolean RepOk(){
        //Effects:ritorna false se il nome del liquido è vuoto o nullo oppure se quantità o parametro sono <0
        if(liquido.equals("")||liquido==null||quantita<0||parametro<0){
            return false;
        }
        return true;
    }

    @Override
    public int compareTo(Contenitore o) {
        if(GetCapienza()>o.GetCapienza()){
            return 1;
        }else if(GetCapienza()<o.GetCapienza()){
            return -1;
        }
        return 0;
    }
}
