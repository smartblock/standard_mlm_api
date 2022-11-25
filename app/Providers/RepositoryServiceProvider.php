<?php

namespace App\Providers;

use App\Interfaces\AdminInterface;
use App\Interfaces\AnnouncementDetailInterface;
use App\Interfaces\AnnouncementInterface;
use App\Interfaces\MemberInterface;
use App\Interfaces\OneTimePasswordInterface;
use App\Interfaces\PasswordResetInterface;
use App\Interfaces\ProductCategoryDetailInterface;
use App\Interfaces\ProductCategoryInterface;
use App\Interfaces\ProductInterface;
use App\Interfaces\ProductPackageItemInterface;
use App\Interfaces\ProductVariantInterface;
use App\Interfaces\RequestLogInterface;
use App\Interfaces\RoleInterface;
use App\Interfaces\SettingInterface;
use App\Interfaces\SponsorLogInterface;
use App\Interfaces\StockGoodReceiveInterface;
use App\Interfaces\StockGoodReceiveItemInterface;
use App\Interfaces\StockLocationInterface;
use App\Interfaces\StockSupplierInterface;
use App\Interfaces\SysCountryInterface;
use App\Interfaces\SysDocNoInterface;
use App\Interfaces\SysLanguageInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\UserProfileInterface;
use App\Interfaces\WalletDetailInterface;
use App\Interfaces\WalletSetupInterface;
use App\Interfaces\WalletSummaryInterface;
use App\Interfaces\WalletTransferInterface;
use App\Repositories\AdminRepository;
use App\Repositories\AnnouncementDetailRepository;
use App\Repositories\AnnouncementRepository;
use App\Repositories\MemberRepository;
use App\Repositories\OneTimePasswordRepository;
use App\Repositories\PasswordResetRepository;
use App\Repositories\ProductCategoryDetailRepository;
use App\Repositories\ProductCategoryRepository;
use App\Repositories\ProductPackageItemRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Repositories\RequestLogRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SettingRepository;
use App\Repositories\SponsorLogRepository;
use App\Repositories\StockGoodReceiveItemRepository;
use App\Repositories\StockGoodReceiveRepository;
use App\Repositories\StockLocationRepository;
use App\Repositories\StockSupplierRepository;
use App\Repositories\SysCountryRepository;
use App\Repositories\SysDocNoRepository;
use App\Repositories\SysLanguageRepository;
use App\Repositories\UserProfileRepository;
use App\Repositories\UserRepository;

use App\Repositories\WalletDetailRepository;
use App\Repositories\WalletSetupRepository;
use App\Repositories\WalletSummaryRepository;
use App\Repositories\WalletTransferRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RequestLogInterface::class, RequestLogRepository::class);

        $this->app->bind(RoleInterface::class, RoleRepository::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(AdminInterface::class, AdminRepository::class);
        $this->app->bind(MemberInterface::class, MemberRepository::class);
        $this->app->bind(UserProfileInterface::class, UserProfileRepository::class);
        $this->app->bind(PasswordResetInterface::class, PasswordResetRepository::class);

        $this->app->bind(OneTimePasswordInterface::class, OneTimePasswordRepository::class);

        $this->app->bind(SysLanguageInterface::class, SysLanguageRepository::class);

        $this->app->bind(SysDocNoInterface::class, SysDocNoRepository::class);
        $this->app->bind(SettingInterface::class, SettingRepository::class);
        $this->app->bind(SysCountryInterface::class, SysCountryRepository::class);

        $this->app->bind(WalletSetupInterface::class, WalletSetupRepository::class);
        $this->app->bind(WalletDetailInterface::class, WalletDetailRepository::class);
        $this->app->bind(WalletSummaryInterface::class, WalletSummaryRepository::class);
        $this->app->bind(WalletTransferInterface::class, WalletTransferRepository::class);

        $this->app->bind(ProductInterface::class, ProductRepository::class);
        $this->app->bind(ProductCategoryInterface::class, ProductCategoryRepository::class);
        $this->app->bind(ProductCategoryDetailInterface::class, ProductCategoryDetailRepository::class);
        $this->app->bind(ProductVariantInterface::class, ProductVariantRepository::class);
        $this->app->bind(ProductPackageItemInterface::class, ProductPackageItemRepository::class);

        $this->app->bind(StockLocationInterface::class, StockLocationRepository::class);
        $this->app->bind(StockSupplierInterface::class, StockSupplierRepository::class);
        $this->app->bind(StockGoodReceiveInterface::class, StockGoodReceiveRepository::class);
        $this->app->bind(StockGoodReceiveItemInterface::class, StockGoodReceiveItemRepository::class);

        $this->app->bind(AnnouncementInterface::class, AnnouncementRepository::class);
        $this->app->bind(AnnouncementDetailInterface::class, AnnouncementDetailRepository::class);

        $this->app->bind(SponsorLogInterface::class, SponsorLogRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
