<?php

namespace Jensramakers\LaravelMediaLibrary\app\Classes;

use Illuminate\Database\Eloquent\Collection;

class NestedTree
{
    /**
     * returns HTML of a tree summary with edit options
     * @param Collection $data expects flat tree data e.g. Model::defaultOrder()->get()->toTree();
     * @param string $url expects a url e.g. "/product/categories" to add /edit, /delete etc.
     * @return string
     */
    public function getTree(Collection $data, string $url): string
    {
        $tree = '<ul class="nested-tree">';

        foreach ($data as $node) {
            $tree .= '<li>' . $node['name'];
            $tree .= '<a style="padding: 5px;" href="/'.$url.'/move-down/' . $node->id . '" class="ml-1 bg-gray-300 hover:bg-gray-400 rounded text-white px-4 inline-block"><i class="fa-solid fa-arrow-down"></i></a>';
            $tree .= '<a style="padding: 5px;" href="/'.$url.'/move-up/' . $node->id . '" class="ml-1 bg-gray-300 hover:bg-gray-400 rounded text-white px-4 inline-block"><i class="fa-solid fa-arrow-up"></i></a>';
            $tree .= '<a style="padding: 5px;" href="/'.$url.'/edit/' . $node->id . '" class="ml-1 bg-yellow-300 hover:bg-yellow-400 rounded text-white px-4 inline-block"><i class="fa-solid fa-pen-to-square"></i></a>';
            $tree .= '<button style="padding: 5px;" type="button" data-bs-toggle="modal" data-bs-target="#deletionModalCenter" onclick="asyncDeletionModal('."'".route('admin.deletion.get.async.modal', ['route' => urlencode(str_replace('/', '\\', "/$url/delete/$node->id"))])."'".')"
                        class="ml-1 bg-red-400 hover:bg-red-500 rounded text-white px-4">
                            <i class="fa-solid fa-trash"></i>
                      </button>';
            $tree .= $this->getTree($node->children, $url);
            $tree .= '</li>';
        }
        $tree .= '</ul>';

        return $tree;
    }

    /**
     * this method returns HTML of options to use in a html select tag
     * @param Collection $data expects flat tree data e.g. Model::withDepth()->defaultOrder()->get()->toFlatTree()
     * @param array $selected array of ids of items that need to be selected by default
     * @return string HTML
     */
    public function getDropDown(Collection $data, array $selected = []): string
    {
        $tree = '';

        foreach ($data as $node) {
            $lines = str_repeat('- ', $node->depth);

            if (in_array($node->id, $selected, true)) {
                $tree .= '<option selected="selected" value="' . $node->id . '">' . $lines . $node->name . '</option>';
            } else {
                $tree .= '<option value="' . $node->id . '">' . $lines . $node->name . '</option>';
            }
        }

        return $tree;
    }
}
