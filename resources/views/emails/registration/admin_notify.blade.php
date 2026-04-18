<x-mail::message>
# Nouvelle demande d'accès secrétaire

Bonjour Monsieur le Directeur,

Une nouvelle demande d'accès secrétaire a été vérifiée et attend votre approbation.

**Détails du compte :**
- **Nom :** {{ $request->name }}
- **Email :** {{ $request->email }}

Vous pouvez approuver ou refuser cette demande directement en cliquant sur les boutons ci-dessous. L'approbation créera automatiquement le compte du secrétaire.

<x-mail::button :url="$approvalUrl" color="primary">
Approuver la demande
</x-mail::button>

<x-mail::button :url="$rejectionUrl" color="error">
Refuser la demande
</x-mail::button>

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
