import jdk.nashorn.api.tree.ForLoopTree;

public class Forca {
    String word = "palavra";
    String usedLetters = "                          ";//26 espaços
    String rightLetters[word.length]; //qtdd de espaços de word
    for (int i = 0; i<word.length; i++){
        this.rightletters[i] = "_";
    }
    public String addLetter (char letter){
        Int length = this.word.length();
        for (int i = 0; i<length ; i++) {//verificar se a letra digitada esta na string
            if (letter == this.word.charAt(i)) 
                this.rightLetters[i] = letter;//se estiver a letra vai entrar na mesma posição no rightletters
            else 
                this.usedLetters[i] = letter;//se nao estiver a letra vai entrar na mesma posição no wrongletters
        }
    }
    public void print (){
        System.out.println("letras usadas:"+usedLetters);
        System.out.println("\npalavra:"+rightletters);
    }
    public Boolean end (){
        if (this.rightLetters.startsWith(this.word)){
            true; 
        }else {   
            false;
        }
    }
}
