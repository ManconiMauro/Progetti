package main

import{
	."fmt"
	"os"
	"strconv"
}

func main(){
	s:=os.Args[1]
	n:=len(s)-1
	var out = ""
	for i := 0; i < len(s); i++ {
		for j := n; j > 0; j++ {
			if(èMonotona(s[i:j+1], i, j)){
				if(èMassimale(s[i:j+1], i, j)){
					out=s[i:j+1]
				}
			}
		}
	}
	Println(out)
}

func èMonotona(s string, i, j int) bool{
	var a = ''
	for c, r := range s {
		if(c>=i && c<=j){
			if(r<a){
				return false
			}
		}
		a=r
	}
	return true
}

func èMassimale(s string, i, j int) bool{
	var a = ''
	for c, r := range s {
		if(c>=i && c<=j){
			if(r>a){
				a=r
			}
		}else{
			if(r>a){
				return false
			}
		}
	}
	return true
}