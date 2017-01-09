package yoshino.controllers.api.user;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;
import yoshino.models.Channel;
import yoshino.services.ChannelService;

import java.security.Principal;
import java.util.HashMap;
import java.util.Map;

/**
 * Created by Volio on 2017/1/7.
 */
@Controller
@RequestMapping("/api/user/channel")
public class UserChannelApiController {

    private final ChannelService channelService;

    @Autowired
    public UserChannelApiController(ChannelService channelService) {
        this.channelService = channelService;
    }

    @PostMapping("/new")
    @ResponseBody
    public Channel createNewChannel(Principal principal) {
        return channelService.createNewChannel(principal.getName());
    }

    @PutMapping("/title")
    @ResponseBody
    public Map<String, Object> updateTitle(@RequestBody Map map, Principal principal) {
        String title = (String) map.get("title");
        Map<String, Object> info = new HashMap<>();
        channelService.updateTitle(principal.getName(), title);
        info.put("info", "修改成功");
        return info;
    }
}
