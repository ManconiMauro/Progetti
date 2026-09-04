public class Cuboide extends Contenitore {
    //Overview:Creare una classe cuboide sottoclasse di contenitore che descriva un contenitore

    //Attributi
    private final double parametro2;
    private final double parametro3;

    //constructor
    public Cuboide(String liquido, double quantita, double parametro, double parametro2, double parametro3) throws IllegalArgumentException, ExceededCapacityException {
        //Modifies:this
        //Effects:inizializza un nuovo contenitore di tipo cilindrico
        //se liquido è vuoto o nullo allora lancia una IllegalArgumentException
        //se quantità, parametro, parametro2 sono <=0 lancia una IllegalArgumentException
        //se la quantità super la capacità del contenitore allora lancia un ExceededCapacityException
        super(liquido, quantita, parametro);
        this.parametro2=parametro2;
        this.parametro3=parametro3;
        if(parametro2<0||parametro3<0){
            throw new IllegalArgumentException("Uno dei paratri inseriti è negativo");
        }
        if(getQuantita()>GetCapienza()){
            throw new ExceededCapacityException("Il cuboide ha capienza: "+GetCapienza()+" ma il liquido ha volume di: "+getQuantita()+")");
        }
        assert repOk();
        assert super.RepOk();
    }

    //methods
    public double getParametro2(){
        //Effects:ritorna il parametro2(l'altezza del cilindro)
        return this.parametro2;
    }

    //methods
    public double getParametro3(){
        //Effects:ritorna il parametro2(l'altezza del cilindro)
        return this.parametro3;
    }

    //methods
    @Override
    public double GetCapienza() {
        //Effects: ritorna la capienza di un contenitore cilindrico
        return getParametro()*getParametro2()*getParametro3();
    }
    
    @Override
    public String toString() {
        //rappresentazione sotto forma di stringa di Cilindro
        return "Cuboide - altezza: "+getParametro()+", raggio: "+getParametro2()+"\n  (capienza: "+GetCapienza()+", contenuto: "+getQuantita()+", liquido: "+getLiquido()+")";
    }

    //Invariante di rappresentazione
    public boolean repOk() {
        //Effects:ritorna false se parametro2 o parametro3 < 0
        if(parametro2<0||parametro3<0){
            return false;
        }
        return true;
     }
}
