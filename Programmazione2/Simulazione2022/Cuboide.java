public class Cuboide extends Contenitore{
    //Overview:Modellare una classe cuboide che implementa Contenitore la classe avrà gli stessei
    //parametri di Contenitore ma al posto di parametriContenitore avrà a,b e c, tre lati
    //la classe modificherà il metodo calcolaVolume in modo da restituire il volume di un cuboide

    //Attributi
    public String nomeLiquido;
    public Double quantitaLiquido;
    public String tipoContenitore;
    public Double a;
    public Double b;
    public Double c;

    //Costruttore
    public Cuboide(String nomeLiquido, Double quantitaLiquido, String tipoContenitore, Double a, Double b, Double c) throws IllegalArgumentException{
        //modifies:this
        //effects:inizializza i parametri e lancia una IllegalArgumetException se questi sono vuotiù
        super(nomeLiquido, quantitaLiquido, tipoContenitore, c);
        if(a==null||b==null||c==null){
            throw new IllegalArgumentException("Dati del contenitore insertiti non corretti");
        }
        this.a=a;
        this.b=b;
        this.c=c;
    }

    @Override
    public Double CalcolaVolume() {
        return this.a*this.b*this.c;
    }
}
