public class PoliticaSconto {
    //Overview:modellare una classe Politica sconto che dati un numero di articoli eun prezzo faccia uno sconto

    //Attributi
    public double prezzoArticolo;
    private int numeroArticolo;

    //Costuttori
    public PoliticaSconto(){

    }

    //getter
    public int getNumeroArticolo() {
        return numeroArticolo;
    }

    //setter
    public void setNumeroArticolo(int numeroArticolo) {
        this.numeroArticolo = numeroArticolo;
    }

    //metodi
    public double calcolaSconto(){

        return 0;
    }
}
