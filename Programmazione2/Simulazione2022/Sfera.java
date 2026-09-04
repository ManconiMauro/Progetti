public class Sfera extends Contenitore{
    //Overview:creare una classe sfera che implementa Contenitore e che utilizza gli stessi parametri 
    //modificendo parametriContenitore usando al posto raggio
    // in più la classe implementerà il metodo calcolaVolume per restituire il volume della sfera

    //Attributi
    public String nomeLiquido;
    public Double quantitaLiquido;
    public String tipoContenitore;
    public Double r;

    //Costruttore
    public Sfera(String nomeLiquido, Double quantitaLiquido, String tipoContenitore, Double r) throws IllegalArgumentException{
        //modifies:this
        //effects:inizializza i parametri e lancia una IllegalArgumetException se questi sono vuoti
        super(nomeLiquido, quantitaLiquido, tipoContenitore, r);
        if(r==null){
            throw new IllegalArgumentException("Dati del contenitore insertiti non corretti");
        }
        this.r=r;
    }

    @Override
    public Double CalcolaVolume() {
        Double r3=Math.pow(this.r, 3.0);
        return r3*3.14*(4/3);
    }
}
