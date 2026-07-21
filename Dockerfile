# Image PHP CLI légère : permet d'exécuter le programme SANS installer PHP
# localement. Une personne non technique n'a besoin que de Docker.
FROM php:8.5-cli-alpine

WORKDIR /app

# On copie uniquement ce qui est nécessaire à l'exécution.
# (Le programme n'a aucune dépendance runtime : bin/ charge src/ directement.)
COPY src/ ./src/
COPY bin/ ./bin/

# Lancement par défaut : affiche la séquence 1 → 6457, une valeur par ligne.
CMD ["php", "bin/pattatras.php"]
