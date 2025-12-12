<?php

namespace Modules\Storefront\Http\ViewComposers;

use Illuminate\View\View;
use Spatie\SchemaOrg\Schema;
use Modules\Storefront\Banner;
use Modules\Storefront\Feature;
use Illuminate\Support\Collection;
use Modules\Product\Entities\Product;
use Spatie\SchemaOrg\ItemAvailability;

class ProductShowPageComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $product = $view->getData()['product'];

        $view->with([
            'features' => Feature::all(),
            'banner' => Banner::getProductPageBanner(),
            'productSchemaMarkup' => $this->schemaMarkup($product),
             'categoryBreadcrumb' => $this->getCategoryBreadCrumb($product->categories->nest()),
        //'categoryBreadcrumb' => $this->getCategoryBreadCrumbFromAncestors($product),

        ]);
    }


    private function schemaMarkup(Product $product)
    {
        $schema = Schema::product()
            ->name($product->name)
            ->sku($product->sku)
            ->url($product->url())
            ->image($product->base_image->path)
            ->brand($this->brandSchema($product))
            ->description($product->short_description)
            ->offers($this->offersSchema($product));

        if ($product->reviews()->count() > 0) {
            $schema->aggregateRating($this->aggregateRatingSchema($product));
        }

        return $schema;
    }


    private function brandSchema(Product $product)
    {
        return Schema::brand()->name($product->brand->name);
    }


    private function aggregateRatingSchema(Product $product)
    {
        return Schema::aggregateRating()
            ->ratingValue($product->reviews()->avg('rating'))
            ->ratingCount($product->reviews()->count());
    }


    private function offersSchema(Product $product)
    {
        return Schema::offer()
            ->price(($product->variant ?? $product)->selling_price->convertToCurrentCurrency()->amount())
            ->priceCurrency(currency())
            ->availability($product->isInStock() ? ItemAvailability::InStock : ItemAvailability::OutOfStock)
            ->url($product->url());
    }


    private function getCategoryBreadCrumb(Collection $categories)
    {
        $breadcrumb = '';

        foreach ($categories as $category) {
            $breadcrumb .= "<li><a href='{$category->url()}'>{$category->name}</a></li>";

            if ($category->items->isNotEmpty()) {
                $breadcrumb .= $this->getCategoryBreadCrumb($category->items);
            }
        }

        return $breadcrumb;
    }
    private function getCategoryBreadCrumbFromAncestors(Product $product): string
    {
        $primaryCategory = $product->categories->first();

        if (!$primaryCategory) {
            return '';
        }

        $path = $this->buildAncestorPath($primaryCategory);

        $breadcrumb = '';

        foreach ($path as $category) {
            $breadcrumb .= "<li><a href='{$category->url()}'>{$category->name}</a></li>";
        }

        return $breadcrumb;
    }

    /**
     * Return array of Category models from root to the given category by following parent_id.
     *
     * @param Category $category
     * @return array<Category>
     */
    private function buildAncestorPath(Category $category): array
    {
        $path = [];
        $current = $category;

        while ($current) {
            $path[] = $current;

            if (empty($current->parent_id)) {
                break;
            }

            $current = Category::find($current->parent_id);

            if (!$current) {
                break;
            }
        }

        return array_reverse($path);
    }


}
