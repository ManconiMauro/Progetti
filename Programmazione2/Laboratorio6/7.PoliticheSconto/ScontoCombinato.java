public class ScontoCombinato extends PoliticaSconto{
    //Overview:modellare una classe che utilizza i parametri di Politica sconto, e che ridefinisce
    //il metodo calcolaSconto per restituire il valore massimo restituito da calcolaSconto per ognuna delle sue politiche di sconto private.

    //Atributi
    public double prezzoArticolo;
    private int numeroArticolo;

    public ScontoCombinato(double prezzoArticolo, int numeroArticolo){
        //modifier:this
        this.prezzoArticolo=prezzoArticolo;
        this.numeroArticolo=numeroArticolo;
    }

    @Override
    public double calcolaSconto() {
        // TODO Auto-generated method stub
        return super.calcolaSconto();
    }
}
