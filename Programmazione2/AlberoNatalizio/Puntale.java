public class Puntale extends Decorazione {
    //Overview:modellare una classe Puntale sottoclasse di Decorazione che identifichi una decorazione unica

    public Puntale(String nome, double peso){
        super(nome, peso);
        assert super.RepOk();
    }
}
