# Balboa Spa
Mit diesem Modul ist es möglich die Balboa Spa Steuerung über IP-Symcon zu benutzen.

   ## Inhaltverzeichnis
   1. [Konfiguration](#1-konfiguration)
   2. [Funktionen](#2-funktionen)

   ## 1. Konfiguration
   
   Feld | Beschreibung
   ------------ | ----------------
   MQTT Base Topic | Base Topic (Standard: homie)
   MQTT Topic | MQTT Topic (Standard: bwa)
   
   ## 2. Funktionen

   ```php
   RequestAction($VariablenID, $Value);
   ```
   Mit dieser Funktion können alle Aktionen einer Variable ausgelöst werden.

   **Beispiel:**
   
   Variable ID Licht 1: 12345
   ```php
   RequestAction(12345, true); //Licht einschalten
   RequestAction(12345, false); //Licht ausschalten
   ```