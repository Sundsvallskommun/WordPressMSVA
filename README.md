_Utveckling sker i dev branch._

# Beroenden

Tema:

* WordPressTivoli som huvudtema (föräldratema).
[WordPressTivoli](https://github.com/Sundsvallskommun/WordPressTivoli)

### Versionsnoteringar 
Utveckla i dev gren. Sammanfoga till master vid ny release och uppdatera versionsnummer enligt nedan struktur.

Större ändringar . Antal ändringar och nya funktioner . Antal åtgärdade buggar

#### 1.8.0

##### Ändring
* Ändrat hur vi plockar ut delar av körkoden för att kunna tillåta text efter körkod.
* Skapat möjlighet att kunna återaktivera ett driftmeddelande.
* Lagt till egna händelser i RSS feed för driftmeddelande.

##### Buggfixar
* Åtgärdat så att det går att redigera filnamn med mera från listvyn i admin.


#### 1.5.0

##### Ändring
* Lagt till så att det går att lägga till egna händelser för driftmeddelanden.
* Lagt till synonymer för FAQ.

##### Buggfixar
* Åtgärdat så att span elementet för synonymer inte läggs till på samtliga posttyper. 


##### Buggfixar
* Åtgärdat omdirigering till startsida vid sökning från startsida vilket uppfattas som ett nollresultat.

#### 1.3.1
##### Buggfixar
* Åtgärdat omdirigering till startsida vid sökning från startsida vilket uppfattas som ett nollresultat.

#### 1.3.0
##### Ändring
* Åtgärdat CDN länkar till att vara SSL kompatibla.

#### 1.2.0
##### Ny funktion
* Synonymfunktion tillagd

#### 1.1.4
##### Buggfixar
* Tagit bort kontroll av "KP" som versaler vid import av körschema, matchar nu både KP som gemener eller versaler.
* Lagt till en bildstorlek som används vid två-kolumn.


#### 1.1.2
##### Buggfixar
* Åtgärd av bugg för körschema sopbil i de fall där samma gatuadress finns för olika områden.
* Tagit bort ramlinje för block där egna färger används.

#### 1.1.0
* Funktionalitet för att kunna hantera egna färger på "block" 

#### 1.0.0 Första relasen