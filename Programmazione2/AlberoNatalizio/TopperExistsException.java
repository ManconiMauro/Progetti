public class TopperExistsException extends RuntimeException {
    
    public TopperExistsException(){
        super("Puntale già esistente");
    }

    public TopperExistsException(String s){
        super(s);
    }
}
