<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Apartment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'address', 'latitude', 'longitude', 'rooms', 'beds', 'bathrooms', 'mq', 'price', 'image', 'slug', 'visibility'];

    // EAGER LOADING
    protected $with = ['sponsors'];

    // * RELAZIONI

    // Relazione con tabella users
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relazione con tabella services
    public function services()
    {
        return $this->belongsToMany(Service::class, 'apartment_service');
    }

    // Relazione con tabella sponsors
    public function sponsors()
    {
        return $this->belongsToMany(Sponsor::class);
    }

    // Relazione con tabella messages
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // * MUTATORS

    protected function getCreatedAtAttribute($value)
    {
        Carbon::setLocale('it');
        $date_from = Carbon::create($value);
        $date_now = Carbon::now();
        return str_replace('prima', 'fa', $date_from->diffForHumans($date_now));
    }

    protected function getUpdatedAtAttribute($value)
    {
        $date_from = Carbon::create($value);
        $date_now = Carbon::now();
        return str_replace('prima', 'fa', $date_from->diffForHumans($date_now));
    }

    // * GETTER

    // Url immagine
    public function getImageUri()
    {
        return $this->image ? url('storage/' . $this->image) : 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQIAAADECAMAAABDV99/AAAAMFBMVEXw8fLR0dHp6+zm6Orv8PHZ2dnj4+Tl5ubb3NzX19fU1NTd3t7p6uvh4eLR0NDz9PXvfehoAAAD5klEQVR4nO3c4XKqMBCG4TcUBAKS+7/bwwJqK7RW5zSZWb/9YQXpFB6TDWwy5aN69+CDtw8RiEAEiAARIAJEgAgQASJABIgAESACRIAIEAEiQASIABEgAkSACBABIkAEiAARIAJEgAgQASJABIgAESACRIAIEAEiQASIABEgAkSACBABIkAEiAARIAJEgAgQASJABIgAESACRIAIKEyQqvbUljyBJYoSdMHilEqeQ1GCqg+h6WIIQ7lzsChGkE5TCGNK6RxCrEo2hFIEdRPi2gWSvTsXNChDkMa5/cdQLxun+V1o6iInYlGEYG38N4KhnbfHUg2hCEETQkfqt+ZvHWL2KDYyFCCot8sd5x8kSwq2d2EpEtkJ0nhp9GkIlhDmcWHZP3eOvkhCyE2QTrcrTe0MMDXVtmU2VebTschMYN/8p/ae6qq+ZQDrIQWSYmaC5ues9+Djv4msBPY1f/x0idZIhtwGOQnmke9RZ7dU0eQ5m2vkI7B01/PoK16Oyjsw/D3B2K4x3wfH9hdhd47b2zwUf04wPwW9GFPIU07JQdA3L0UMeXppDoI2vRSNJ4J5OHw67DHKEYENdc9G7Y4gPgcQHRJ0z/UFj62ge+q2VwSuCdqhO//iedgvQbsmxcfVYrcE4xTi+TTY09KDcEsQl9dUx4dTaF4J2hDXLDA/Dz/4Ja8EXThv++JaNP4+/BKctl39EUH9KUl6JRguZcH5Avf1szpMt7KiV4Jxu/I0hH53a2CV1fnAy5ZTgtSFOP+5eggH/aAP/XCroHslsOtcZ9D2qwn6EGubUjtfu4pTAs4G0O/ukK2+WK0V9PU4xwRQjfWuCaRL10jtNovgmuAa3bU3WAfYasUXg3cgqPvrzJk1/+tQwDqT8gYENrW2GdgX/zk92kf+q0bLSoK6XQ3G+4nU+Q4hjs4Jtg6fFoMxTPdpwgxclk+v12nZb1llsRjE0OyLSL3PCvI1/9+WEJjB8VR647ojfF5IktpvVlR4Toc2GLZf08KRgWOCZUHVl0+PDfwSVDYY3n18aOCVIH0craE5NHBKML9MR+l/uT282+eU4Nv1dKmd7ovqPgmGo0rJFrve4ZIgfBkM74/ZbXskiLEfut+HQ4Lp3RfaMJ6eDm/LrXh+yZ0vguE362734Yng5XBCwPDa8luLB/PP/ykyQL+2/HbJB1lC/79ABCJABIgAESACRIAIEAEiQASIABEgAkSACBABIkAEiAARIAJEgAgQASJABIgAESACRIAIEAEiQASIABEgAkSACBABIkAEiAARIAJEgAgQASJABIgAESACRIAIEAEiQASIABEgAkSACBABIoB/gKk49BhDy7wAAAAASUVORK5CYII=';
    }

    // * SUPPORTO

    // Generazione slug unico - Funzione statica per generare uno slug unico che aggiunge un "-" più un numero crescente se riscontra nel DB uno slug uguale a quello che il sistema prova ad inserire
    public static function generateUniqueSlug($title)
    {
        $generated_slug = Str::of($title)->slug('-');

        $apartments = Apartment::where('slug', $generated_slug)->get();

        $i = 1;
        $original_slug = $generated_slug;
        while (count($apartments)) {
            $generated_slug = $original_slug . "-" . ++$i;
            $apartments = Apartment::where('slug', $generated_slug)->get();
        }

        return $generated_slug;
    }

    // * HTML

    // Funzione che restituisce un icona html
    public function getIconHTML()
    {
        if ($this->visibility == 1) {
            return '<i class="bi bi-check-lg"></i>';
        } else {
            return '<i class="bi bi-x-lg"></i>';
        }
    }
}