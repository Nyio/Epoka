package com.example.epoka;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.os.StrictMode;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

public class Epoka_authentification extends Activity {

    EditText numero,mdp;
    String url_serveur = "http://192.168.1.86/Epoka/Epoka_Web/Services/connexion.php?user=";
    // String url_serveur_lycee = "http://172.16.47.6/epoka/svc_authentification.php?no=";
    JSONObject resultat;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.epoka_authentification);
        numero = findViewById(R.id.edt_No);
        mdp = findViewById(R.id.edt_Mdp);
    }

    public void oui_click(View view) throws JSONException {
        String url = url_serveur + numero.getText() + "&mdp=" + mdp.getText();
        resultat = getServerDataJson(url);
        Button btn = findViewById(R.id.btn_connexion);

        if(resultat.has("erreur")){
            Toast.makeText(getApplicationContext(),"Erreur de connexion",Toast.LENGTH_SHORT).show();
        }else{
            Intent intent = new Intent(getApplicationContext(), Epoka_accueil.class);
            intent.putExtra("no", resultat.getInt("sal_id"));
            intent.putExtra("nom", resultat.getString("sal_nom"));
            intent.putExtra("prenom", resultat.getString("sal_prenom"));
            intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK + Intent.FLAG_ACTIVITY_NEW_TASK);
            startActivity(intent);
        }
    }

    private JSONObject getServerDataJson(String urlString) throws JSONException{
        InputStream is = null;
        String result = "";

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

        JSONArray jArray = new JSONArray(result);
        JSONObject jsonData = null;
        for (int i = 0; i < jArray.length(); i++){
            jsonData = jArray.getJSONObject(i);
        }
        return jsonData;
    }
}
