public class Sfera extends Contenitore {
    //Overview:Creare una classe sfera sottoclasse di contenitore che descriva un contenitore 

    //constructor
    public Sfera(String liquido, double quantita, double parametro) throws IllegalArgumentException, ExceededCapacityException {
        //Modifies:this
        //Effects:inizializza un nuovo contenitore di tipo cilindrico
        //se liquido è vuoto o nullo allora lancia una IllegalArgumentException
        //se quantità, parametro, parametro2 sono <=0 lancia una IllegalArgumentException
        //se la quantità super la capacità del contenitore allora lancia un ExceededCapacityException
        super(liquido, quantita, parametro);
        super.RepOk();
    }

    //methods
    @Override
    public double GetCapienza() {
        //Effects: ritorna la capienza di un contenitore cilindrico
        return (Math.PI*(Math.pow(getParametro(), 3))*4)/3;
    }
    
    @Override
    public String toString() {
        //rappresentazione sotto forma di stringa di Cilindro
        return "Sfera - raggio: "+getParametro()+"\n  (capienza: "+GetCapienza()+", contenuto: "+getQuantita()+", liquido: "+getLiquido()+")";
    }
}
