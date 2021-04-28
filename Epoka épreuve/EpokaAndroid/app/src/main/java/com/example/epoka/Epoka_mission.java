package com.example.epoka;

import android.app.Activity;
import android.content.Intent;
import android.icu.text.StringPrepParseException;
import android.os.Bundle;
import android.os.StrictMode;
import android.util.Log;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;

public class Epoka_mission extends Activity {

    int no;
    EditText dateFin;
    EditText dateDeb;
    String url_serveur_mission = "http://192.168.1.86/Epoka/Epoka_Web/Services/missions.php?dateDebut=";

    List<LibelleEtId> list_ville;
    Spinner villes;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.epoka_mission);
        list_ville = new ArrayList<LibelleEtId>();
        String url_serveur_ville = "http://192.168.1.86/Epoka/Epoka_Web/Services/villes.php";
        getServerDataJson(url_serveur_ville);
        ArrayAdapter<LibelleEtId> adapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, list_ville);
        villes = (Spinner) findViewById(R.id.spVille);
        villes.setAdapter(adapter);
    }

    public class LibelleEtId{
        public String libelle;
        public int cp;
        public String resultat;
        public int id;

        public LibelleEtId(String unLibelle, int unId, int unCp){
            libelle = unLibelle;
            id = unId;
            cp = unCp;

            resultat = libelle + " (" + cp + ") ";
        }

        @Override
        public String toString(){
            return resultat;
        }
    }

    public void ajoutMission(View view){
        dateDeb = findViewById(R.id.edtDateDeb);
        dateFin = findViewById(R.id.edtDateFin);
        LibelleEtId libelleEtId = (LibelleEtId) villes.getSelectedItem();
        int idVille = libelleEtId.id;
        Intent intent = getIntent();
        no = intent.getIntExtra("no", 0);
        String urlAjoutMission = url_serveur_mission + dateDeb.getText() + "&dateFin=" + dateFin.getText() + "&destination=" + idVille + "&salarie=" + no;

        InputStream is = null;
        String rep;

        try {
            //Si problèmes de connexion
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
            // Echange HTTP avec le serveur
            URL url = new URL(urlAjoutMission);
            HttpURLConnection connexion = (HttpURLConnection) url.openConnection();
            connexion.connect();
            is = connexion.getInputStream();

            Toast.makeText(this,"Mission ajoutée" , Toast.LENGTH_SHORT).show();
            finish();

        }
        catch(Exception expt){
            Log.e("log_tag", "Erreur pendant la récupération des données : " + expt.toString());
        }

    }

    private void getServerDataJson(String urlString){
        InputStream is = null;
        String result = "";
        List<LibelleEtId>tableau=new ArrayList<>();

        try {
            //Si problèmes de connexion
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
            // Echange HTTP avec le serveur
            URL url = new URL(urlString);
            HttpURLConnection connexion = (HttpURLConnection) url.openConnection();
            connexion.connect();
            is = connexion.getInputStream();

            // Exploitation de la réponse
            BufferedReader br = new BufferedReader(new InputStreamReader(is));
            String ligne;
            while ((ligne = br.readLine()) != null) {
                result = result + ligne + "\n";
            };
        }
        catch(Exception expt){
            Log.e("log_tag", "Erreur pendant la récupération des données : " + expt.toString());
        }

        //Parse les données JSON
        try {
            JSONArray jarray = new JSONArray(result);
            for (int i = 0; i < jarray.length(); i++) {
                JSONObject jsonData = jarray.getJSONObject(i);
                LibelleEtId ville = new LibelleEtId(jsonData.getString("vil_nom"), jsonData.getInt("vil_id"), jsonData.getInt("vil_cp"));
                tableau.add(ville);
            }
        } catch (JSONException expt) {
            Log.e("log-tag", "Erreur pendant l'analyse des données : " + expt.toString());
        }

        for (int i = 0; i < tableau.size(); i++) {
            list_ville.add(new LibelleEtId(tableau.get(i).libelle, tableau.get(i).id, tableau.get(i).cp));
        }
    }
}
