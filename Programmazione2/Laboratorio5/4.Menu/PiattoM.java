public class PiattoM {
    //Overview:modellare una calsse piatto che modelli un piatto con nome, tipo e costo

    //attributi
    public String nome;
    public String tipo;
    public Double costo;

    //costruttore
    public PiattoM(String nome, String tipo, Double costo) throws IllegalArgumentException{
        //modifies:this
        //effects:Crea un nuovo piatto usando nome tipo e costo e chiama una illegalArgumentException
        if(nome.equals(" ")||tipo.equals(" ")||costo==null){
            throw new IllegalArgumentException("Dati del piatto inseriti non validi");
        }
        this.nome=nome;
        this.tipo=tipo;
        this.costo=costo;
    }
}
