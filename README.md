# 🌍 Installation locale de FreeBenin

---

## 🛠 Prérequis

- Windows, macOS ou Linux
- XAMPP installé
- Node.js installé (nécessaire pour LocalTunnel)

---

## 1. ⚙️ Installation de XAMPP

1. Téléchargez [XAMPP](https://www.apachefriends.org/index.html)
2. Installez-le sur votre machine
3. Lancez XAMPP et démarrez :
   - **Apache**
   - **MySQL**

---

## 2. 📁 Configuration du projet FreeBenin

1. Placez les fichiers du projet FreeBenin dans le répertoire `htdocs` de XAMPP :  
   `C:\xampp\htdocs\FreeBenin` *(ou selon l'emplacement de votre installation)*

2. Vérifiez que le fichier de création automatique de la base de données (`install.php` ou équivalent) contient le script PHP suivant :
   - Il crée la base **freelance_benin**
   - Il génère toutes les tables nécessaires
   - Il gère les contraintes de clés étrangères et les colonnes par défaut

   ```php
   const BDD_HOST = 'localhost';
   const BDD_NAME = 'freelance_benin';
   const BDD_USER = 'root';
   const BDD_PASSWORD = '';
   // ...script de création...


---


---

## 🔐 Activer les privilèges administrateur

Pour accéder à l’interface d’administration :

1. Ouvrez **phpMyAdmin**
2. Sélectionnez la base `freelance_benin`
3. Dans la table `inscription`, localisez votre ligne utilisateur
4. Mettez à jour la colonne `admin` :
```text
admin = 'admin'

 💳 Activer le système de paiement avec LocalTunnel

Pour tester les paiements en local, il est nécessaire d’avoir une URL publique. Voici comment faire :

1. Installez LocalTunnel :
   npm install -g localtunnel
2. Lancez le serveur PHP sur le port de votre choix :
    php -S localhost:8081
    (8081 est juste un exemple vous pouvez prendre n'importe quel port tant qu'il n'est pas occupe)
3. Dans une autre invite de commande, exposez ce port avec LocalTunnel :
    lt --port 8081