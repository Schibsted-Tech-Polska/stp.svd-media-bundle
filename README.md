# SvD Media Bundle
## Configuration
```yaml
svd_media:
    adapter:                            gaufrette_service_id
    base_url:                           file_folder_url
```

## Usage
Add ralation to your entity annotation.
```php
/**
* @OneToOne(targetEntity="File")
* @JoinColumn(name="file_id", referencedColumnName="id")
**/
protected $file; 
```

To form type add `media` field
```php
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder->add('file', MediaType::class)
}
```

To remove unused media from storage, run command:
```
app/console media:remove-unused
```
