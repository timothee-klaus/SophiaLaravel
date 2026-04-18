<x-mail::message>
<div style="text-align: center; margin-bottom: 30px;">
    <h1 style="color: #1e3a8a; margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">INSTITUT SCOLAIRE SOPHIA</h1>
    <p style="color: #64748b; font-size: 14px; margin-top: 5px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Le Don de Dieu</p>
</div>

# Nouvelle demande d'accès secrétaire

Bonjour **Monsieur le Directeur**,

Une nouvelle demande d'accès secrétaire a été vérifiée et attend votre approbation finale.

<div style="background-color: #f1f5f9; border-radius: 12px; padding: 20px; margin: 20px 0; border-left: 4px solid #1e3a8a;">
    <p style="margin: 0; font-weight: 700; color: #334155; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Détails du postulant</p>
    <table style="width: 100%; margin-top: 10px;">
        <tr>
            <td style="color: #64748b; font-size: 14px; width: 80px;">Nom :</td>
            <td style="color: #1e293b; font-size: 14px; font-weight: 700;">{{ $request->name }}</td>
        </tr>
        <tr>
            <td style="color: #64748b; font-size: 14px;">Email :</td>
            <td style="color: #1e293b; font-size: 14px; font-weight: 700;">{{ $request->email }}</td>
        </tr>
    </table>
</div>

Veuillez prendre une décision concernant cette demande en utilisant les options ci-dessous :

<table style="width: 100%; text-align: center; margin: 30px 0;">
    <tr>
        <td style="padding: 10px;">
            <a href="{{ $approvalUrl }}" style="display: inline-block; padding: 14px 28px; background-color: #1e3a8a; color: #ffffff; text-decoration: none; border-radius: 10px; font-weight: 800; font-size: 14px; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(30, 58, 138, 0.2);">
                APPROUVER LA DEMANDE
            </a>
        </td>
        <td style="padding: 10px;">
            <a href="{{ $rejectionUrl }}" style="display: inline-block; padding: 14px 28px; background-color: #ffffff; color: #ef4444; text-decoration: none; border-radius: 10px; font-weight: 800; font-size: 14px; border: 2px solid #fee2e2; transition: all 0.2s;">
                REFUSER
            </a>
        </td>
    </tr>
</table>

L'approbation créera automatiquement le compte sécurisé pour ce secrétaire.

Cordialement,<br>
**{{ config('app.name') }}**
</x-mail::message>
