public class Decorazione {
    //Overview:creare una classe che rappresenti una decorazione formata da nome e peso

    //Attributi
    //ho deciso di fare tutti gli attributi final perchè non dovranno essere modificati
    private final String nome;
    private final double peso;

    //constructor
    public Decorazione(String nome, double peso) throws IllegalArgumentException{
        //modifies:this.nome,this.peso
        //effects:Crea un nuovo oggetto decorazione inserendo dentro a nome e peso i valori dati
        //in caso il nome sia nullo o vouto viene chiamata una IllegalArgumentException
        //in caso il peso sia <=0(ho pensato che una decorazione non può non avere un peso o averlo negativo) lancia una IllegalArgumentException
        if(nome.equals("")||nome==null){
            throw new IllegalArgumentException("Nome inserito nullo o vouto");
        }
        if(peso<=0){
            throw new IllegalArgumentException("Peso inserito non valido");
        }
        this.nome=nome;
        this.peso=peso;
        assert RepOk();
    }

    //getter
    public String getNome() {
        //effects:ritorna il nome della decorazione
        return nome;
    }

    //getter
    public double getPeso() {
        //effects:ritorna il peso della decorazione
        return peso;
    }



    @Override
    public String toString() {
        //effects:converte un oggetto Decorazione in una stringa per poterlo visualizzare
        return "Decorazione: "+this.nome+"; peso: "+this.peso;
    }

    //invariante di rappresentazione
    public boolean RepOk(){
        if(nome.equals("")||nome==null){
            return false;
        }
        if(peso<=0){
            return false;
        }
        return true;
    }
}
