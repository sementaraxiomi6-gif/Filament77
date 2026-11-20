<?php  
  
namespace App\Filament\Admin\Resources\Carts;  
  
use App\Filament\Admin\Resources\Carts\Pages\ListCarts;  
use App\Filament\Admin\Resources\Carts\Schemas\CartForm;  
use App\Filament\Admin\Resources\Carts\Tables\CartsTable;  
use App\Models\Cart;  
use BackedEnum;  
use Filament\Resources\Resource;  
use Filament\Schemas\Schema;  
use Filament\Support\Icons\Heroicon;  
use Filament\Tables\Table;  
  
class CartResource extends Resource  
{  
    protected static ?string $model = Cart::class;  
  
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static string|\UnitEnum|null $navigationGroup = 'Carts & Orders';
  
    protected static ?string $recordTitleAttribute = 'name';  

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function canCreate(): bool
    {
        return false;
    }
  
    public static function form(Schema $schema): Schema  
    {  
        return CartForm::configure($schema);  
    }  
  
    public static function table(Table $table): Table  
    {  
        return CartsTable::configure($table);  
    }  
  
    public static function getRelations(): array  
    {  
        return [  
            //  
        ];  
    }  
  
    public static function getPages(): array  
    {  
        return [  
            'index' => ListCarts::route('/'),
        ];  
    }  
}