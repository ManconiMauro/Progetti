public class Persona {
    //Overview:Creare una classe persona con un solo attributo, il nome e che abbia un getter e un costruttore

    //Attributi
    private String nome;

    //costruttori
    public Persona(){
        this.nome="";
    }

    public Persona(String nome){
        //modifies:this
        //effects:inizializza una persona con un nome si considera che il nome che viene passato sia già nel formato giusto
        this.nome=nome;
    }

    //getter
    public String getNome() {
        return nome;
    }

    //setter
    public void setNome(String nome) {
        //modifies:this
        this.nome = nome;
    }
}


