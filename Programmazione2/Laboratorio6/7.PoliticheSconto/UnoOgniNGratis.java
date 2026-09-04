public class UnoOgniNGratis extends PoliticaSconto{
    //Overview:La classe ha un singolo parametro n. la classe ridefinisce il metodo calcolaSconto così che ogni n-esimo articolo sia gratis.

    //Attributi
    private int n;

    //Costruttori
    public UnoOgniNGratis(int n){
        this.n=n;
    }

    @Override
    public boolean calcolaSconto(int numero) {
        if(numero=n){
            return 0;
        }  
        retur      return super.calcolaSconto();
    }
}
