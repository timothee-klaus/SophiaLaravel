# Documentation de l'API de l'Institut Sophia

**Format :** JSON natif via Laravel Sanctum.
**Authentification :** Renseigner le token en Header HTTP `Authorization: Bearer <TOKEN>`.
**Domaine :** `<BASE_URL>/api`

---

## 1. Authentification

**Endpoint :** `POST /login` (À implémenter dans votre AuthController classique)
**Paramètres requis :** `email`, `password`

---

## 2. Espace Directeur (Dashboard)

**Endpoint :** `GET /director/dashboard`
**Rôle Requis :** `director`

Fournit les métriques critiques : total perçu, retards de paiement, taux de recouvrement par cycle.

**Exemple de Requête :**
```http
GET /api/director/dashboard
Authorization: Bearer 1|xxx...xxx
```

**Exemple de Réponse JSON :**
```json
{
  "total_collected": 150000.00,
  "late_students": [
    {
      "id": 4,
      "name": "Élève 4 Test",
      "missing_installment": 1,
      "due_amount": 10000.0
    }
  ],
  "recovery_rate_by_cycle": {
    "preschool": "50 %",
    "primary": "0 %",
    "college": "80 %",
    "lycee": "45 %"
  }
}
```

---

## 3. Enregistrement d'un Paiement

**Endpoint :** `POST /payments`
**Rôle Requis :** `secretary`

Gère l'enregistrement et vérifie si le montant demandé ne dépasse pas l'échéance. Cet endpoint supporte l'envoi du reçu via une requête Multi-part. Le paiement déclenche une notification temps réel au directeur via WebSockets.

**Headers :** `Content-Type: multipart/form-data`

**Paramètres requis :**
- `student_id` (int)
- `academic_year_id` (int)
- `type` (enum: 'registration', 'miscellaneous', 'tuition')
- `amount` (float)
- `installment_number` (int, optionnel : requis si c'est la scolarité)
- `receipt` (file, optionnel, max 2Mo, mimes: jpeg, png, pdf)

**Exemple de Réponse JSON (201 Created) :**
```json
{
  "success": true,
  "message": "Paiement enregistré avec succès.",
  "payment": {
    "id": 12,
    "student_id": 4,
    "academic_year_id": 1,
    "amount": "10000.00",
    "type": "tuition",
    "installment_number": 1,
    "transaction_id": "RCPT-20260410143000-AB12",
    "receipt_path": "receipts/RCPT-20260410143000-AB12.pdf",
    "created_at": "2026-04-10T14:30:00.000000Z"
  }
}
```

---

## 4. Visualisation de Reçu (Sécurisée)

**Endpoint :** `GET /director/payments/{payment}/receipt-url`
**Rôle Requis :** `director`

Génère une URL signée valable 5 minutes pour télécharger ou visualiser le reçu d'un paiement (stocké en privé).

**Exemple de Réponse JSON :**
```json
{
  "url": "https://<app-domain>/api/download/receipt/12?expires=16462444...&signature=..."
}
```

---

## 5. Résumé Financier Élève

*L'endpoint standard qui revoie les étudiants utilisant `StudentResource` produira cette structure :*

**Exemple de format généré par la ressource :**
```json
{
  "id": 4,
  "name": "Élève 4 Test",
  "matricule": "MAT-2025-004",
  "birth_date": "2014-01-01",
  "cycle": "college",
  "total_paid": 16000.00,
  "balance_remaining": 22000.00,
  "is_eligible_for_exams": true
}
```
