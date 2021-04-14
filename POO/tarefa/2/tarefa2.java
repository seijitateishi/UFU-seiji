/*public class Ponto {
    private float x;
    private float y;

    public float distancia(float ax, float ay, float bx, float by){
        return sqrt((ax-bx)*(ax-bx)+(ay-by)*(ay-by));
    }/*retorna a distancia de dois pontos

    public float getx(){
        return x;
    }
    public void setx(float x){
        this.x = x;
    }
    public float gety(){
        return y;
    }
    public void sety(float y){
        this.y = y;
    }
    public Ponto (float x , float y){
        this.x=x;
        this.y=y;
    }
    public static void main (String[] args){
        Ponto p = new Ponto(2,2);
        p.setx(1);
        p.sety(1);
        System.out.println("ponto x = " + p.x + p.y);
    }
}

public class Circulo{
    float raio;
    Ponto centro;
    String nome;
    public float diametro(){
        return 2*raio;
    }
    public float area(){
        return 3*raio*raio;
    }
    public float circunferencia(){
        return 6*raio;
    }
    public String toString() {
        return "Circulo [nome=" + nome + ", diametro=" + diametro + ", area="
                + area + ", circunferencia=" + circunferencia + "]";
    }
    public void setnome(String nome){
        this.nome = nome;
    }
    public String getnome(){
        return nome;
    }
    public Circulo(float raio,Ponto centro){
        this.centro=centro;
        this.raio=raio;
    }
    public static void main (String[] args){
        Circulo c = new Circulo(3,(0,0),"circulo1");
        setnome("circulo2");
        System.out.println(toString());
    }
}
/**1 - Crie a classe Circulo. 
Crie um construtor para inicializar a instância que recebe como 
parâmetros o raio e o valor do centro, que é um ponto em duas dimensões. 
Utilize a classe Ponto.
Adicione como atributos, também, um nome.
Adicione como métodos:
Calcular diâmetro
Calcular área
Calcular circunferência
Acessar e modificar nome (não pode ser vazio)
Exibir os dados em um método.
2 - Crie a classe Ponto que possui duas dimensões (float x, float y).
Crie os métodos get e set.
Faça o main para instanciar e testar a classe.
Adicione o método distancia (float x, float y) que 
calcula a distância do ponto às coordenadas (x,y). 
Teste!
Sobrecarregue o com o método distancia(Ponto p). Teste!*/