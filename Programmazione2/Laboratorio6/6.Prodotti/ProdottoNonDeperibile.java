public class ProdottoNonDeperibile extends Prodotto {
    //Overview:modella una classe di un prodotto non deperibile che può essere riciclabile o meno
    public ProdottoNonDeperibile(String nome,Double costo,String DatoProdotto) throws NotValidProdotto{
        super(nome,costo,DatoProdotto);
    }

    @Override
    public int getSconto() {
        if(getDatoProdotto().equals("riciclabile")){
            return 10;
        }
        return 5;
    }
}
