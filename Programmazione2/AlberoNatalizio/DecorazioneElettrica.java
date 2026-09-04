public class DecorazioneElettrica extends Decorazione implements Comparable<DecorazioneElettrica> {
    //Overview:Modellare una classe sottotipo di decorazione che abbia in più una potenza elettrica e una interrutore

    //Attriuti 
    //ho deciso di fare l'attributo interrutore modificabile ma privato perchè esiste una funzione che andrà a modificarlo(in questa classe)
    private final double potenza;
    private boolean interruttore;

    //costruttore
    public DecorazioneElettrica(String nome, double peso, double potenza)throws IllegalArgumentException{
        //modifies:this.nome,this.peso,this.potenza e this.interruttore
        //effects:inizializza una nouva decorazione elettrica con i parametri dati, tranne interrutore che sarà inizializzato a false
        //in caso la potenza sia <=0(ho fatto un ragionamento simile al caso del peso) lancio una IllegalArgumentException
        super(nome,peso);
        if(potenza<=0){
            throw new IllegalArgumentException("Potenza inserita minore o uguale a 0");
        }
        this.potenza=potenza;
        this.interruttore=false;
        assert super.RepOk();
        assert RepOk();
    }

    //methods
    public void Interruttore(){
        //modifies:this.interruttore
        //effects:modifica il valore di interruttore in true per segnare che la decorazione è accesa
        this.interruttore=true;
    }

    //getter
    public double getPotenza() {
        //effects:ritorna la potenza della decorazione elettrica
        return potenza;
    }

    //Invariante di rappresentazione
    public boolean RepOk(){
        if(potenza<=0){
            return false;
        }
        return true;
    }

    @Override
    public int compareTo(DecorazioneElettrica o) {
        if(this.potenza<o.potenza){
            return -1;
        }else if(this.potenza>o.potenza){
            return 1;
        }
        return 0;
    }

    @Override
    public String toString() {
        //effects:converte un oggetto Decorazione in una stringa per poterlo visualizzare
        String stampa="Decorazione: "+super.getNome()+"; peso: "+super.getPeso()+", potenza: "+this.potenza;
        if(interruttore){
            return stampa+" accesa";
        }
        return stampa+" spenta";
    }
}
