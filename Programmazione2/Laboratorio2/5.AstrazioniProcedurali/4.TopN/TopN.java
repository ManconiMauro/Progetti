import java.util.ArrayList;
import java.util.Scanner;

public class TopN {
    
    

    private static ArrayList<Integer> readIntArray(){
        //requires:bisogna che l'utente scriva un totale di numeri maggiori di n(numero preso da Args)
        //Effects:legge da standard input una serie di numeri interi e la restituisce 

        //Variabile per contenere l'array di interi
        ArrayList<Integer> lista=new ArrayList<>();

        Scanner s=new Scanner(System.in);
        while(s.hasNext()){
            int app=s.nextInt();
            lista.add(app);
        }

        return lista;
    }

    public static ArrayList<Integer> highest(ArrayList<Integer> numbers, int n){

        //Vettore per contenere i numeri più grandi
        ArrayList<Integer> max=new ArrayList<>();

        while(n>0){
            int maximus=-1000000001;
            int control;
            for (int i = 0; i < numbers.size(); i++) {
                if(numbers.get(i)>maximus){
                    maximus=numbers.get(i);
                    control=i;
                }
            }
            max.add(maximus);
            int temp=numbers.get(numbers.size()-1);
            numbers.set(numbers.size()-1, numbers.get(control));
            numbers.set(control, temp);
            numbers.remove(control);
        }

        return max;
    }

    private static void printArray(ArrayList<Integer> numbers){
        System.out.println(numbers);
    }

    public static void main(String[] args) {
        ArrayList<Integer> lista=readIntArray();
        int n=Integer.parseInt(args[0]);
        ArrayList<Integer> massimi=highest(lista, n);
        printArray(massimi);
    }
}
