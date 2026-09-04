import java.time.LocalDate;
import java.time.Period;
import java.time.format.DateTimeFormatter;

public class ProdottoAlimentare extends Prodotto {
    //Overview:modella una classe di un prodotto alimentare con data di scadenza

    //costruttori
    public ProdottoAlimentare(String nome, Double costo, String DatoProdotto) throws NotValidProdotto{
        //modifies:this
        //effects:inizializza this con nome,costo e scadenza 
        super(nome,costo,DatoProdotto);
    }

    @Override
    public int getSconto() {
        LocalDate data = LocalDate.parse(getDatoProdotto(), DateTimeFormatter.ofPattern("d-M-yyyy"));
        if(LocalDate.now().plusDays(10).isAfter(data)){
            return 30;
        }
        return 5;
    }
}