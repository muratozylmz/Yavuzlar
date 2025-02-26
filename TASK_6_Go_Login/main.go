package main

import (
	"bufio"
	"encoding/json"
	"fmt"
	"os"
	"time"
)

type User struct {
	Username string `json:"username"`
	Password string `json:"password"`
	Role     string `json:"role"`
}

func LoadUsers() ([]User, error) {
	file, err := os.Open("users.json")
	if err != nil {
		defaultUsers := []User{
			{Username: "admin", Password: "admin", Role: "admin"},
		}
		saveUsers(defaultUsers)
		return defaultUsers, nil
	}
	defer file.Close()

	var users []User
	err = json.NewDecoder(file).Decode(&users)
	return users, err
}

func saveUsers(users []User) error {
	file, err := os.Create("users.json")
	if err != nil {
		return err
	}

	defer file.Close()
	return json.NewEncoder(file).Encode(users)
}

func logFile(message string) {
	file, err := os.OpenFile("log.txt", os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0644)
	if err != nil {
		fmt.Println("Log dosyasına yazılamadı...", err)
		return
	}
	defer file.Close()
	logMessage := fmt.Sprintf("%s - %s\n", time.Now().Format("2006-01-02 15:04:05"), message)
	file.WriteString(logMessage)
}

func login(username, password string, users []User) (User, bool) {
	for _, user := range users {
		if user.Username == username && user.Password == password {
			logFile(fmt.Sprintf("User %s logged in", username))
			return User{}, true
		}
	}
	logFile(fmt.Sprintf("Failed login attempt for user %s", username))
	return User{}, false
}

func Admin(users *[]User) {
	scanner := bufio.NewScanner(os.Stdin)
	for {
		fmt.Println("Admin Menu:")
		fmt.Println("1. Müşteri Ekle")
		fmt.Println("2. Müşteri Sil")
		fmt.Println("3. Log Görüntüle")
		fmt.Println("4. Çıkış")
		fmt.Print("Seçiminiz Nedir?: ")
		scanner.Scan()
		choice := scanner.Text()

		switch choice {
		case "1":
			addCustomer(users)
		case "2":
			deleteCustomer(users)
		case "3":
			viewLogs()
		case "4":
			return
		default:
			fmt.Println("Geçersiz bir seçim yaptınız. Tekrar deneyiniz.")
		}
	}
}

func Customer(user User) {
	scanner := bufio.NewScanner(os.Stdin)
	for {
		fmt.Println("Müşteri Menüsü:")
		fmt.Println("1. Profili Görüntüle")
		fmt.Println("2. Şifre Değiştirme")
		fmt.Println("3. Çıkış")

		fmt.Print("Seçiminiz Nedir?: ")
		scanner.Scan()
		choice := scanner.Text()

		switch choice {
		case "1":
			viewProfile(user)
		case "2":
			changePassword(&user)
		case "3":
			return
		default:
			fmt.Println("Geçersiz bir seçim yaptınız. Tekrar deneyiniz.")
		}
	}
}

func addCustomer(users *[]User) {
	scanner := bufio.NewScanner(os.Stdin)
	fmt.Println("Müşteri Ekleme Menu:")
	fmt.Print("Müşteri Adı: ")
	scanner.Scan()
	username := scanner.Text()

	fmt.Print("Müşteri Sifresi: ")
	scanner.Scan()
	password := scanner.Text()

	*users = append(*users, User{username, password, "customer"})
	logFile(fmt.Sprintf("Müşteri eklendi: %s", username))
	fmt.Println("Müşteri eklendi.")

	err := saveUsers(*users)
	if err != nil {
		fmt.Println("Müşteri ekleme hatası:", err)
	}
}

func deleteCustomer(users *[]User) {
	scanner := bufio.NewScanner(os.Stdin)
	fmt.Println("Müşteri Silme Menu:")
	fmt.Print("Müşteri Adı: ")
	scanner.Scan()
	username := scanner.Text()

	for i, user := range *users {
		if user.Username == username && user.Role == "customer" {
			*users = append((*users)[:i], (*users)[i+1:]...)
			logFile(fmt.Sprintf("Müşteri silindi: %s", username))
			fmt.Println("Müşteri silindi.")

			err := saveUsers(*users)
			if err != nil {
				fmt.Println("Müşteri silme hatası:", err)
			}
			return
		}
	}
	fmt.Println("Müşteri bulunamadı.")
}

func viewLogs() {
	file, err := os.Open("log.txt")
	if err != nil {
		fmt.Println("Log dosyası bulunamadı.", err)
		return
	}
	defer file.Close()

	fmt.Println("Loglar:")
	scanner := bufio.NewScanner(file)
	for scanner.Scan() {
		fmt.Println(scanner.Text())
	}

}

func viewProfile(user User) {
	fmt.Printf("Profil: %s\n", user.Username)
}

func changePassword(user *User) {
	scanner := bufio.NewScanner(os.Stdin)
	fmt.Print("Yeni Sifre: ")
	scanner.Scan()
	newPassword := scanner.Text()

	user.Password = newPassword
	logFile(fmt.Sprintf("Sifre değiştirildi: %s", user.Username))
	fmt.Println("Sifre değiştirildi.")
}
func main() {
	users, err := LoadUsers()
	if err != nil {
		fmt.Println("Kullanıcılar yüklenemedi:", err)
		return
	}

	scanner := bufio.NewScanner(os.Stdin)
	fmt.Println("0 - Admin Giriş Yap, 1 - Müşteri Giriş Yap")

	for {
		fmt.Print("Seçiminiz Nedir?: ")
		scanner.Scan()
		userType := scanner.Text()
		if userType != "0" && userType != "1" {
			fmt.Println("Geçersiz bir seçim yaptınız. Tekrar deneyiniz.")
			continue
		}

		fmt.Print("Kullanıcı Adı: ")
		scanner.Scan()
		username := scanner.Text()

		fmt.Print("Sifre: ")
		scanner.Scan()
		password := scanner.Text()

		user, success := login(username, password, users)
		if success {
			if userType == "0" && user.Role == "admin" {
				fmt.Println("Admin Giriş Yapıldı.")
				Admin(&users)
			} else if userType == "1" && user.Role == "customer" {
				fmt.Println("Müşteri Giriş Yapıldı.")
				Customer(user)
			} else {
				fmt.Println("Yetkisiz kullanıcı.")
			}
		} else {
			fmt.Println("Kullanıcı adı veya sifre yanlış.")
		}
	}
}
