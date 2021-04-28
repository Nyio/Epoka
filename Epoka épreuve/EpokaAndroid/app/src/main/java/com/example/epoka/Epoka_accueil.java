package com.example.epoka;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONException;

public class Epoka_accueil extends Activity {

    int no;

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.epoka_accueil);
        TextView tv_info = findViewById(R.id.txtViewIntro);
        Intent intent = getIntent();

        no = intent.getIntExtra("no", 0);
        String nom = intent.getStringExtra("nom");
        String prenom = intent.getStringExtra("prenom");
        tv_info.setText("Bonjour " + nom + " " + prenom);

    }

    public void newMission_click(View view) throws JSONException {
        Button btn = findViewById(R.id.btn_connexion);

        Intent intent = new Intent(getApplicationContext(), Epoka_mission.class);
        intent.putExtra("no", no);
        intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK + Intent.FLAG_ACTIVITY_NEW_TASK);
        startActivity(intent);

    }

    public void exitApp(View v){
        finish();
        System.exit(0);
    }
}
