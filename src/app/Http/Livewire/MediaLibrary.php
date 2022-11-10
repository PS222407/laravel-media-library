<?php

namespace Jensramakers\LaravelMediaLibrary\app\Http\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jensramakers\LaravelMediaLibrary\app\Classes\NestedTree;
use Jensramakers\LaravelMediaLibrary\app\Models\File;
use Jensramakers\LaravelMediaLibrary\app\Models\FileCategory;
use Jensramakers\LaravelMediaLibrary\app\Traits\MimeIcon;
use Livewire\Component;
use Livewire\WithFileUploads;

class MediaLibrary extends Component
{
    use WithFileUploads;
    use MimeIcon;

    public $uploadFiles = [];
    public $uploadIteration = 0;
    public $currentTab = 1;
    public $multiple = true;
    public $uploadLabel = 'FRONT';
    public $galleryId, $options, $imgCategory, $containerElementId, $form, $inputName, $files, $imgLabel, $editFile, $deleteFile, $createCategoryName, $parentCategory, $childrenOfCategory;
    public Collection $previousParentCategory;

    public function mount()
    {
        $this->previousParentCategory = new Collection();
        $this->parentCategory = FileCategory::firstWhere('name', 'root');
        $this->files = $this->parentCategory->files;
        $this->imgCategory = $this->parentCategory->id;
    }

    /*
     * lifecycle hook; Updated
     */
    public function updatedUploadFiles()
    {
        $this->validate([
            'uploadFiles.*' => ['required', 'max:'.(1024 * 10)],
        ]);
    }

    /*
     * sets variable of current formtab
     * css classes hide some html blocks based on the $currentTab value
     */
    public function changeTab($currentTab)
    {
        $this->currentTab = $currentTab;
    }

    /*
     * opens edit modal with corresponding image
     * Fires openEditModal event which javascript catches and opens the modal showing editFile
     */
    public function edit($id)
    {
        $this->editFile = $this->files->find($id);
        $this->imgLabel = $this->editFile->label;
        $this->imgCategory = $this->editFile->file_category_id;

        $imageDirectoriesFlat = FileCategory::withDepth()->defaultOrder()->get()->toFlatTree();
        $this->options = (new NestedTree())->getDropDown($imageDirectoriesFlat);

        $this->emit('openEditModal');
    }

    /*
     * update the editFile to database
     */
    public function update()
    {
        $this->editFile->update([
            'label' => $this->imgLabel !== "" ? $this->imgLabel : null,
            'file_category_id' => $this->imgCategory,
        ]);
        $this->emit('closeModal');
        $this->files = FileCategory::find($this->parentCategory->id)->files;
    }

    /*
     * handles uploaded images/files
     */
    public function store()
    {
        $this->validate([
            'uploadFiles' => ['required'],
            'uploadFiles.*' => ['required', 'max:'.(1024 * 10)],
        ]);

        foreach ($this->uploadFiles as $uploadFile) {
            $filename = Str::slug(Str::random(11).'-'.$uploadFile->getClientOriginalName()).'.'.$uploadFile->extension();
            $path = $uploadFile->storeAs('public/uploadFileStorage', $filename);

            File::create([
                'name' => $filename,
                'path' => str_replace($filename, '', $path),
                'mime' => $uploadFile->getMimeType(),
                'mime_icon' => $this->getIconByMimeTYpe($uploadFile->getMimeType()),
                'label' => $this->uploadLabel,
                'file_category_id' => $this->parentCategory->id,
            ]);
        }

        $this->currentTab = 1;
        $this->uploadFiles = [];
        $this->uploadIteration++;

        $this->files = FileCategory::find($this->parentCategory->id)->files;
        $this->dispatchBrowserEvent('imgDirChanged');
    }

    public function deleteConfirmation($id)
    {
        $this->deleteFile = $this->files->find($id);
        $this->emit('openDeleteModal');
    }

    public function deleteFile()
    {
        Storage::delete($this->deleteFile->path.$this->deleteFile->name);
        $this->deleteFile->delete();
        $this->emit('closeModal');
        $this->files = FileCategory::find($this->parentCategory->id)->files;
    }

//    ===========    categories    ===========

    /*
     * creates a category and put it in current parent folder, and reload folders from database
     */
    public function createCategory()
    {
        $this->validate([
            'createCategoryName' => 'required',
        ]);

        FileCategory::create([
            'name' => $this->createCategoryName,
        ], $this->parentCategory);

        $this->createCategoryName = null;
        $this->dispatchBrowserEvent('imgDirChanged');
    }

    /*
     * changes directory and load child directories, push the directory to history collection
     */
    public function changeParentDir($id)
    {
        $this->previousParentCategory->push($this->parentCategory);
        $this->parentCategory = FileCategory::find($id);

        $this->files = $this->parentCategory->files;
        $this->dispatchBrowserEvent('imgDirChanged');
    }

    /*
     * switch from directory by clicking on breadcrumb
     */
    public function changeParentDirByHistoryCollection($id)
    {
        $this->parentCategory = FileCategory::find($this->previousParentCategory[$id]->id);

        $collection = New Collection();
        for ($i = 0; $i < $id; $i++) {
            $collection->push($this->previousParentCategory[$i]);
        }
        $this->previousParentCategory = $collection;

        $this->files = $this->parentCategory->files;
        $this->dispatchBrowserEvent('imgDirChanged');
    }

    /*
     * go back to previous directory, remove from history collection
     */
    public function backToPreviousDir()
    {
        if ($this->previousParentCategory->count() === 0) {
            return;
        }

        $this->parentCategory = $this->previousParentCategory->last();
        $this->previousParentCategory->forget($this->previousParentCategory->count() -1);

        $this->files = $this->parentCategory->files;
        $this->dispatchBrowserEvent('imgDirChanged');
    }

    public function render()
    {
        $this->childrenOfCategory = FileCategory::find($this->parentCategory->id)->children->loadCount('files');

        return view('laravel-media-library::media-library');
    }
}
