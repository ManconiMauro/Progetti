public class Cilindro extends Contenitore{
    //Overview:creare una classe cilindro che implementa Contenitore e che utilizza gli stessi parametri 
    //modificendo parametriContenitore usando al posto raggio e h.
    // in piiù la classe implementerà il metodo calcolaVolume per restituire il volume del cilindro

    //Attributi
    public String nomeLiquido;
    public Double quantitaLiquido;
    public String tipoContenitore;
    public Double r;
    public Double h;

    //Costruttore
    public Cilindro(String nomeLiquido, Double quantitaLiquido, String tipoContenitore, Double r, Double h) throws IllegalArgumentException{
        //modifies:this
        //effects:inizializza i parametri e lancia una IllegalArgumetException se questi sono vuoti
        super(nomeLiquido,quantitaLiquido,tipoContenitore, h);
        if(r==null||h==null){
            throw new IllegalArgumentException("Dati del contenitore insertiti non corretti");
        }
        this.r=r;
        this.h=h;
    }

    @Override
    public Double CalcolaVolume() {
        Double r2=Math.pow(r, 2.0);
        return r2*3.14*h;
    }
}
