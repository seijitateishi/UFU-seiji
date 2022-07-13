import javax.swing.JOptionPane;
import java.util.ArrayList;
import java.util.List;
//Luis Gustavo Seiji Tateishi
//11921BCC034
public class Empresa {
    public static void main(String[] args) {
        Livro l1 = new Livro("A menina que roubava livros", (float) 25.89, 5, 100, "Marcus Zusak");
        Livro l2 = new Livro("1984", (float) 21.50, 4, 200, "George Orwell");
        CD cd1 = new CD("Banda Calypso", (float) 29.99, 1, 1500, 2);
        CD cd2 = new CD("The Wall", (float) 65.00, 2, 1000, 23);
        DVD dvd1 = new DVD("Shrek", (float) 15.50, 10, 1337, 95);
        DVD dvd2 = new DVD("Shrek 2", (float) 16.50, 11, 420, 105);
        ArrayList <ItemDeEstoque> estoque = new ArrayList<ItemDeEstoque>();
        estoque.add(l1);
        estoque.add(l2);
        estoque.add(cd2);
        estoque.add(cd2);
        estoque.add(dvd1);
        estoque.add(dvd2);
        try {
            estoque.get(0).abaixaEstoque(2);
            estoque.get(1).elevarEstoque(150);
            estoque.get(2).abaixaEstoque(2);
            estoque.get(3).elevarEstoque(1200);
        } catch (EstoqueBaixoException | EstoqueElevadoException e) {
            JOptionPane.showMessageDialog(null,e.getMessage(),"Erro",JOptionPane.ERROR_MESSAGE);
        }
        int i = 0;
        float valorinvestido = 0;
        int estoquetotal = 0;
        while (estoque.get(i) != null){
            estoquetotal += estoque.get(i).qtdEstoque;
            valorinvestido += (estoque.get(i).qtdEstoque*estoque.get(i).preco);
            System.out.println(estoque.get(i).toString());
            i++;
        }
        System.out.println("Estoque total :" + estoquetotal);
        System.out.println("Preco investido :" + valorinvestido);
    }
}
