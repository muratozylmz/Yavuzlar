package main

import (
	"flag"
	"fmt"
	"log"
	"strings"

	"github.com/gocolly/colly"
)

func main() {
	siteSelection := flag.String("site", "", "Haber sitesi seçmenizi sağlar. \n Örnek Kullanım: -site '1 veya 2' \n\n 1 - The Hacker News \n 2 - Ajansspor")
	dateFilter := flag.Bool("date", false, "Tarih bilgilerini göster.\n Örnek Kullanım: -site '1 veya 2' -date\n")
	descriptionFilter := flag.Bool("desc", false, "Acıklama bilgilerini göster.\n Örnek Kullanım: -site '1 veya 2' -desc\n")
	flag.Parse()

	if *siteSelection == "" {
		log.Fatal("Eksik parametre gönderildi. Açıklamalar ve örnek kullanım için lütfen '-h' parametresini kullanın.")
	}
	sites := strings.Split(*siteSelection, ",")

	for _, site := range sites {
		switch site {
		case "1":
			getNews1("https://thehackernews.com/", *dateFilter, *descriptionFilter)
		case "2":
			getNews2("https://ajansspor.com/", *dateFilter, *descriptionFilter)
		default:
			log.Fatal("Gecersiz site seçildi.")
		}

	}

}

func getNews1(url string, dateFilter bool, descriptionFilter bool) {
	c := colly.NewCollector(colly.AllowedDomains("thehackernews.com"))

	c.OnError(func(r *colly.Response, err error) {
		fmt.Printf("Error: %s\n", err.Error())
	})

	c.OnHTML("div.body-post", func(h *colly.HTMLElement) {
		selection := h.DOM

		title := selection.Find("h2.home-title").Text()
		date := selection.Find("span.h-datetime").Text()
		trimmedDate := date[3:]
		description := selection.Find("div.home-desc").Text()
		newsLink := selection.Find("a.story-link").AttrOr("href", "")
		if !dateFilter && !descriptionFilter {
			fmt.Printf("Haber Başlığı: %s\n\n Tarih: %s\n\n Açıklama: %s...\n\n Haber Linki: %s\n\n", title, trimmedDate, description, newsLink)
		} else if dateFilter && !descriptionFilter {
			fmt.Printf("Haber Başlığı: %s\n\n Tarih: %s\n\n Haber Linki: %s\n\n", title, trimmedDate, newsLink)
		} else if !dateFilter && descriptionFilter {
			fmt.Printf("Haber Başlığı: %s\n\n Açıklama: %s...\n\n Haber Linki: %s\n\n", title, description, newsLink)
		} else {
			fmt.Printf("Haber Başlığı: %s\n\n Tarih: %s\n\n Açıklama: %s...\n\n Haber Linki: %s\n\n", title, trimmedDate, description, newsLink)
		}
	})

	c.Visit(url)
}

func getNews2(scrapeURL string, dateFilter bool, descriptionFilter bool) {
	c := colly.NewCollector(colly.AllowedDomains("ajansspor.com"))

	c.OnError(func(r *colly.Response, err error) {
		fmt.Printf("Error: %s\n", err.Error())
	})

	c.OnHTML("div.card", func(h *colly.HTMLElement) {
		selection := h.DOM

		title := selection.Find("div.news-title").Text()

		date := selection.Find("div.news-date-bottom").Text()
		trimmedDate := date

		description := selection.Find("div.post-content").Text()

		newsLink := selection.Find("a").AttrOr("href", "")

		if !dateFilter && !descriptionFilter {
			fmt.Printf("Haber Başlığı: %s\n\n Tarih: %s\n\n  Açıklama: %s...\n\n Haber Linki: %s\n\n", title, trimmedDate, description, newsLink)
		} else if dateFilter && !descriptionFilter {
			fmt.Printf("Haber Başlığı: %s\n\n Haber Linki: %s\n\n", title, newsLink)
		} else if !dateFilter && descriptionFilter {
			fmt.Printf("Haber Başlığı: %s\n\n Tarih: %s\n\n ", title, trimmedDate)
		} else {
			fmt.Printf("Haber Başlığı: %s\n\n", title)
		}
	})

	c.Visit(scrapeURL)
}
