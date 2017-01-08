package yoshino.controllers.api.publicity;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import yoshino.services.ChannelService;

import java.util.Map;

/**
 * Created by Volio on 2017/1/8.
 */
@RestController
@RequestMapping("/api/channels")
public class ChannelApiController {

    private final ChannelService channelService;

    @Autowired
    public ChannelApiController(ChannelService channelService) {
        this.channelService = channelService;
    }

    @GetMapping("/{id}/playurl")
    public Map<String, String> getPlayUrl(@PathVariable("id") Long id) {
        return channelService.getPlayUrl(id);
    }
}
