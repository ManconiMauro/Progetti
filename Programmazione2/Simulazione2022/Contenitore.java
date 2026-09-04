public abstract class Contenitore {
    //Overview:creare una classe contenitore che abbia le specifiche di un contenitore di vario tipo

    //Attributi
    public String nomeLiquido;
    public Double quantitaLiquido;
    public String tipoContenitore;

    //Costruttore
    public Contenitore(String nomeLiquido, Double quantitaLiquido, String tipoContenitore, Double parametriContenitore) throws IllegalArgumentException{
        //modifies:this
        //effects:inizializza i parametri e lancia una IllegalArgumetException se questi sono vuoti
        if(nomeLiquido.equals("")||quantitaLiquido==null||tipoContenitore.equals("")){
            throw new IllegalArgumentException("Dati del contenitore insertiti non corretti");
        }
        this.nomeLiquido=nomeLiquido;
        this.quantitaLiquido=quantitaLiquido;
        this.tipoContenitore=tipoContenitore;
    }

    //metodi
    public Double CalcolaVolume(){
        return 0.0;
    }

    public void TrovaCapacita() throws ExceededCapacityException{
        if(this.quantitaLiquido>=CalcolaVolume()){
            throw new ExceededCapacityException("Il contenitore ha capienza: "+this.CalcolaVolume()+"ma il liquido ha volume di: "+this.quantitaLiquido);
        }
    }
}

 