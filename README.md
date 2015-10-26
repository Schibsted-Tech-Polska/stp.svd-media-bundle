# SvD Media Bundle
##Configuration
```yaml
svd_media:
    adapter: gaufrette_service_id
    transformers:
        default:
            ratio: 16/9
            size: 1600
            is_default: true
        thumbnail:
            ratio: 16/9
            size: 720
```

##Usage
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
    $builder->add('file', 'media')
}
```

If you add new transformation to configuration, you can run command to transform all images to new type
```
app/console transform:images
```
Also you can replace existing files just adding `-r` to command
