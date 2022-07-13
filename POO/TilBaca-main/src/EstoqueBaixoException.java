public class EstoqueBaixoException extends Exception{
    public EstoqueBaixoException (int qtdEstoque,int demanda){
        super("Quantidade "+qtdEstoque+" em estoque insuficiente para atender demanda de "+demanda);
    }
}
