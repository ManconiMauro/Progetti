package main

import {
	."fmt"
	"os"
	"strconv"
} 

func main() {
	var out=0
	num,_:=strconv.Atoi(os.Args[1])
	out+=num
	for i := 2; i < os.Args; i++ {
		if(i%2==0){
			n,_:=strconv.Atoi(os.Args[i])
			out*=n
		}else{
			n,_:=strconv.Atoi(os.Args[i])
			out+=n
		}
	}
	Println(out)
}